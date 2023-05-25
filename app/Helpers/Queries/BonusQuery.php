<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   BonusQuery.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries;

use App\Helpers\Queries\Filters\PeriodFilter;
use App\Helpers\Queries\Filters\UserRoleFilter;
use App\Models\Bonus;

class BonusQuery extends Query
{
    protected $model = Bonus::class;

    protected $with = ['account', 'account.user', 'account.user.profiles', 'account.user.referrer'];

    protected $filters = [[PeriodFilter::class], 'account.user' => [UserRoleFilter::class]];

    protected $searchableColumns = [['id'], 'account.user' => ['name', 'email']];

    protected $sortableColumns = [
        'id'            => 'id',
        'amount'        => 'amount',
        'created_ago'   => 'created_at',
    ];
}
