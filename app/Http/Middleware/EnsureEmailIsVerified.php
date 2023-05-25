<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   EnsureEmailIsVerified.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;

class EnsureEmailIsVerified
{
    
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        
        if (config('settings.users.email_verification') && !$request->user()->hasVerifiedEmail()) {
            return $request->expectsJson()
                ? abort(HTTP_CODE_EMAIL_NOT_VERIFIED)
                : Redirect::to('/email');
        }

        return $next($request);
    }
}
