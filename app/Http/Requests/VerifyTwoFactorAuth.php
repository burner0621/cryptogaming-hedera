<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   VerifyTwoFactorAuth.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Rules\OneTimePasswordIsCorrect;
use Illuminate\Foundation\Http\FormRequest;

class VerifyTwoFactorAuth extends FormRequest
{
    
    public function authorize()
    {
        return !$this->user()->two_factor_auth_passed;
    }

    
    public function rules()
    {
        return [
            'one_time_password' => [
                'required',
                new OneTimePasswordIsCorrect($this->user()->totp_secret)
            ],
        ];
    }
}
