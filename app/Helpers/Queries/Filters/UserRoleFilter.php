<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   UserRoleFilter.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRoleFilter extends Filter
{
    protected $key = 'roles';

    public function buildQuery(Builder $query): Builder
    {
        return $query->whereIn(
            'users.role',
            collect($this->getValue())
                ->map(function ($role) { return constant(User::class . '::ROLE_' . strtoupper($role)); })
                ->filter() 
        );
    }
}
