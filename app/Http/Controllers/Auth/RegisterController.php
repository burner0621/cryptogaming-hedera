<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   RegisterController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Rules\ReCaptchaValidationPassed;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    

    use RegistersUsers;

    
    protected function registered(Request $request, User $user)
    {
        return UserRepository::load($user);
    }

    
    protected function validator(array $data)
    {
        $rules = [
            'name'                  => 'required|min:3|max:100|unique:users',
            'email'                 => 'required|email|max:255|unique:users',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ];

        if (config('services.recaptcha.secret_key')) {
            $rules['recaptcha'] = ['required', new ReCaptchaValidationPassed()];
        }

        foreach (config('settings.users.fields') as $field) {
            if ($field->validation) {
                $rules['fields.' . $field->id] = $field->validation;
            }
        }

        return Validator::make($data, $rules);
    }

    
    protected function create(array $data)
    {
        return UserRepository::create(collect($data)->only('name', 'email', 'password', 'fields'));
    }
}
