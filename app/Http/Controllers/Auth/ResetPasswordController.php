<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ResetPasswordController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    

    use ResetsPasswords;

    
    protected function rules()
    {
        return [
            'token'     => 'required',
            'email'     => 'required|email',
            'password'  => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
        ];
    }
}
