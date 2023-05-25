<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PasswordController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\User;

use App\Http\Requests\UpdatePassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    
    public function update(UpdatePassword $request)
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
    }
}
