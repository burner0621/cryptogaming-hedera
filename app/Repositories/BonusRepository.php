<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   BonusRepository.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Repositories;

use App\Facades\AccountTransaction;
use App\Models\Account;
use App\Models\Bonus;

class BonusRepository
{
    
    public static function create(Account $account, int $type, float $amount): ?Bonus
    {
        if ($amount == 0) {
            return NULL;
        }

        
        $bonus = new Bonus();
        $bonus->account()->associate($account);
        $bonus->type = $type;
        $bonus->amount = $amount;
        $bonus->save();

        AccountTransaction::create($account, $bonus, $amount);

        return $bonus;
    }
}
