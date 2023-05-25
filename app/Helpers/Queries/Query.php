<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Query.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries;

use App\Helpers\Queries\Filters\Filter;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Query
{
    
    protected $model;

    protected $request;
    protected $rowsCount;

    
    protected $whereClauses = [];

    
    protected $filters = [];

    
    protected $with = [];

    
    protected $sortableColumns = [];

    
    protected $defaultOrderBy = 'id';

    
    protected $defaultOrderDirection = 'desc';

    
    protected $itemsPerPageOptions = [5, 10, 25, 50];

    
    protected $defaultItemsPerPage = 10;

    
    protected $searchableColumns = [];

    
    public function __construct()
    {
        if (!$this->model && get_called_class() != __CLASS__) {
            throw new Exception('Model property should be set when instantiating a new Query class.');
        }

        $this->request = request();

        return $this;
    }

    
    public static function make(string $model): Query
    {
        return tap(new self(), function ($instance) use ($model) {
            $instance->model = $model;
        });
    }


    
    public function getRequest(): Request
    {
        return $this->request;
    }

    
    public function getRowsCount(): int
    {
        if (is_null($this->rowsCount)) {
            $this->rowsCount = $this->calculateRowsCount();
        }

        return $this->rowsCount;
    }

    
    protected function calculateRowsCount(): int
    {
        return $this->getScopedBuilder()->count();
    }

    
    public function getRowsToSkip(): int
    {
        return ($this->getPage() - 1) * $this->getItemsPerPage();
    }

    
    public function getItemsPerPage(): int
    {
        $itemsPerPage = $this->request->query('items_per_page');
        return in_array($itemsPerPage, $this->itemsPerPageOptions) ? $itemsPerPage : $this->defaultItemsPerPage;
    }

    
    public function getPage(): int
    {
        $page = (int) $this->request->query('page');
        return max(1, min($this->getPagesCount(), $page));
    }

    
    public function getOrderBy(): string
    {
        $orderBy = $this->request->query('sort_by') ?: $this->defaultOrderBy;

        return empty($this->sortableColumns)
            ? $this->defaultOrderBy
            : (array_key_exists($orderBy, $this->sortableColumns) 
                ? $this->sortableColumns[$orderBy]
                : (in_array($orderBy, $this->sortableColumns)
                    ? $orderBy
                    : $this->defaultOrderBy));
    }

    
    public function getOrderDirection(): string
    {
        $orderDirection = $this->request->query('sort_direction');

        return in_array($orderDirection, ['asc', 'desc'])
            ? $orderDirection
            : $this->defaultOrderDirection;
    }

    
    public function getSearchString(): ?string
    {
        $search = $this->request->query('search');

        return strlen($search) <= 20 ? $search : substr($search, 0, 20);
    }

    
    public function search(Builder $query): Builder
    {
        $search = $this->getSearchString();

        $addSearchClause = function ($columns, $query) use ($search) {
            foreach ($columns as $i => $column) {
                $function = $i == 0 ? 'whereRaw' : 'orWhereRaw';

                [$clause, $args] = $column == 'id' || Str::endsWith($column, '.id')
                    ? [$column . ' = ?', [is_numeric($search) ? $search : NULL]]
                    : ['LOWER(' . $column . ') LIKE ?', ['%' . strtolower($search) . '%']];

                $query->{$function}($clause, $args);
            }
        };

        
        $query->where(function ($query) use ($addSearchClause) {
            collect($this->searchableColumns)->each(function ($columns, $relation) use ($query, $addSearchClause) {
                
                $query->orWhere(function ($query) use ($relation, $addSearchClause, $columns) {
                    
                    if (is_string($relation)) {
                        $query->whereHas($relation, function ($query) use ($addSearchClause, $columns) { $addSearchClause($columns, $query); });
                    } else {
                        $addSearchClause($columns, $query);
                    }
                });
            });
        });

        return $query;
    }

    
    public function where(Builder $query): Builder
    {
        collect($this->whereClauses)->each(function ($clause) use ($query) {
            if (is_array($clause)) {
                $query->where(...$clause);
            
            } else {
                $query->where($clause);
            }
        });

        return $query;
    }

    
    protected function getBaseBuilder(): Builder
    {
        return $this->model::query();
    }

    
    protected function getScopedBuilder(): Builder
    {
        $builder = $this->getBaseBuilder();

        
        collect($this->filters)->each(function ($filters, $relation) use ($builder) {
            collect($filters)->each(function ($filterClass) use ($builder, $relation) {
                tap(new $filterClass(is_string($relation) ? $relation : NULL), function (Filter $filter) use ($builder) {
                    $builder->when(!is_null($filter->getValue()), Closure::fromCallable([$filter, 'getQuery']));
                });
            });
        });

        return $builder
            ->when($this->hasWhereClauses(), [$this, 'where'])
            ->when($this->hasSearch(), [$this, 'search']);
    }

    
    public function getBuilder(): Builder
    {
        return $this->getScopedBuilder()
            ->with($this->with)
            ->skip($this->getRowsToSkip())
            ->take($this->getItemsPerPage())
            ->orderByRaw($this->getOrderBy() . ' ' . $this->getOrderDirection());
    }

    
    public function get(): Collection
    {
        return $this->getBuilder()->get();
    }

    public function hasWhereClauses(): bool
    {
        return !empty($this->whereClauses);
    }

    public function hasSearch(): bool
    {
        return $this->getSearchString() && !empty($this->searchableColumns);
    }

    public function addWhere($clauses): Query
    {
        $this->whereClauses[] = $clauses;

        return $this;
    }

    public function addFilters(array $filters): Query
    {
        $this->filters = $filters;

        return $this;
    }

    public function addOrderBy(array $sortableColumns): Query
    {
        $this->sortableColumns = $sortableColumns;

        return $this;
    }

    
    protected function getPagesCount(): int
    {
        return (int) ceil($this->getRowsCount() / $this->getItemsPerPage());
    }
}
