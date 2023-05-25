<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ForgotPasswordController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Auth;

use App\Rules\ReCaptchaValidationPassed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    

    use SendsPasswordResetEmails;

    
    protected function validateEmail(Request $request)
    {
        $rules = ['email' => 'required|email'];

        if (config('services.recaptcha.secret_key')) {
            $rules['recaptcha'] = ['required', new ReCaptchaValidationPassed()];
        }

        $request->validate($rules);
    }
}
