<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   EnumFilter.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries\Filters;

use Illuminate\Database\Eloquent\Builder;

class EnumFilter extends Filter
{
    protected $model;
    protected $table;

    public function buildQuery(Builder $query): Builder
    {
        $const = $this->model . '::' . strtoupper($this->key) . '_' . strtoupper($this->getValue());

        return $query->when(defined($const), function (Builder $query, bool $defined) use ($const) {
            $query->where($this->table . '.' . $this->key, constant($const));
        });
    }
}
