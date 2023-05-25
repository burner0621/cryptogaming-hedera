<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CheckSocialProvider.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;

class CheckSocialProvider
{
    
    public function handle($request, Closure $next)
    {
        if (!config('oauth.'.$request->provider.'.client_id')
            || !config('oauth.'.$request->provider.'.client_secret')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
