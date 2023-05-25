<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   BalanceIsSufficient.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class BalanceIsSufficient implements Rule
{
    private $user;
    private $requiredAmount;

    
    public function __construct(User $user, $requiredAmount)
    {
        $this->user = $user;
        $this->requiredAmount = floatval($requiredAmount);
    }

    
    public function passes($attribute = NULL, $value = NULL)
    {
        return $this->user->account->balance >= $this->requiredAmount;
    }

    
    public function message()
    {
        return __('Insufficient balance to perform this operation.');
    }
}
