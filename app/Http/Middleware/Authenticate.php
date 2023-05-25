<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Authenticate.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return redirect('/login');
        }
    }
}
