<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AccountCredit.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AccountCredit extends FormRequest
{
    
    public function authorize()
    {
        return TRUE;
    }

    
    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:1|max:' . (PHP_INT_MAX - $this->account->balance)
        ];
    }
}
