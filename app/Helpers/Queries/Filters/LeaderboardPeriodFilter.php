<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LeaderboardPeriodFilter.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries\Filters;

use Illuminate\Database\Eloquent\Builder;

class LeaderboardPeriodFilter extends PeriodFilter
{
    public function buildQuery(Builder $query): Builder
    {
        return $query->period($this->getValue(), 'games');
    }
}
