<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PeriodFilter.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries\Filters;

use Illuminate\Database\Eloquent\Builder;

class PeriodFilter extends Filter
{
    protected $key = 'period';

    public function buildQuery(Builder $query): Builder
    {
        return $query->period($this->getValue());
    }
}
