<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AffiliateCommissionStatusFilter.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries\Filters;

use App\Models\AffiliateCommission;

class AffiliateCommissionStatusFilter extends EnumFilter
{
    protected $key = 'status';
    protected $model = AffiliateCommission::class;
    protected $table = 'affiliate_commissions';
}
