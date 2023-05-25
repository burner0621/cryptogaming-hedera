<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   UserQuery.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries;

use App\Helpers\Queries\Filters\PeriodFilter;
use App\Helpers\Queries\Filters\UserRoleFilter;
use App\Helpers\Queries\Filters\UserStatusFilter;
use App\Models\User;

class UserQuery extends Query
{
    protected $model = User::class;

    protected $with = ['profiles', 'referrer'];

    protected $filters = [[PeriodFilter::class, UserRoleFilter::class, UserStatusFilter::class]];

    protected $searchableColumns = [['id', 'name', 'email', 'code']];

    protected $sortableColumns = [
        'id'            => 'id',
        'name'          => 'name',
        'email'         => 'email',
        'created_ago'   => 'created_at',
        'last_seen_ago' => 'last_seen_at',
    ];
}
