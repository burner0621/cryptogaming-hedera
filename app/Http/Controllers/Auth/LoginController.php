<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LoginController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Rules\ReCaptchaValidationPassed;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    

    use AuthenticatesUsers;

    
    protected function credentials(Request $request)
    {
        
        return array_merge($request->only($this->username(), 'password'), ['status' => User::STATUS_ACTIVE]);
    }

    
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ];

        if (config('services.recaptcha.secret_key')) {
            $rules['recaptcha'] = ['required', new ReCaptchaValidationPassed()];
        }

        $request->validate($rules);
    }

    
    protected function authenticated(Request $request, $user)
    {
        $user->last_login_at = Carbon::now();
        $user->last_login_from = $request->ip();
        $user->save();

        return UserRepository::load($user);
    }

    
    protected function guard()
    {
        return Auth::guard('web');
    }
}
