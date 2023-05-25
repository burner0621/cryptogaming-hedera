<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AccountDebit.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AccountDebit extends FormRequest
{
    
    public function authorize()
    {
        return TRUE;
    }

    
    public function rules()
    {


        return [
            'amount' => 'required|numeric|min:1|max:' . $this->account->balance
        ];
    }
}
