<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PercentageAffiliateCommission.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

interface PercentageAffiliateCommission
{
    
    public function getAffiliateCommissionBaseAmount(): float;
}
