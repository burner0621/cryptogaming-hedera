<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LogoutIfBlocked.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class LogoutIfBlocked
{
    
    public function handle($request, Closure $next)
    {
        
        if (!$request->user()->is_active) {
            auth()->guard('web')->logout();
            abort(401);
        }

        return $next($request);
    }
}
