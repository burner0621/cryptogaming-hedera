<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AffiliateCommissionTypeFilter.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries\Filters;

use App\Models\AffiliateCommission;

class AffiliateCommissionTypeFilter extends EnumFilter
{
    protected $key = 'type';
    protected $model = AffiliateCommission::class;
    protected $table = 'affiliate_commissions';
}
