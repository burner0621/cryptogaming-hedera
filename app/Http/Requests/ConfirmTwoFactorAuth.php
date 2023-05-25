<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ConfirmTwoFactorAuth.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Rules\OneTimePasswordIsCorrect;
use Illuminate\Foundation\Http\FormRequest;

class ConfirmTwoFactorAuth extends FormRequest
{
    
    public function authorize()
    {
        
        return !$this->user()->two_factor_auth_enabled;
    }

    
    public function rules()
    {
        return [
            'secret' => 'required|string|size:32',
            'one_time_password' => [
                'required',
                new OneTimePasswordIsCorrect($this->secret),
            ]
        ];
    }
}
