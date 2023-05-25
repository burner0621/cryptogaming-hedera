<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   UserStatusFilter.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries\Filters;

use App\Models\User;

class UserStatusFilter extends EnumFilter
{
    protected $key = 'status';
    protected $model = User::class;
    protected $table = 'users';
}
