<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AccountTransaction.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AccountTransaction extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'account_transaction';
    }
}
