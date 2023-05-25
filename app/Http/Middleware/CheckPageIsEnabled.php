<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CheckPageIsEnabled.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;

class CheckPageIsEnabled
{
    
    public function handle($request, Closure $next)
    {
        if (!config('settings.content.leaderboard.enabled') && $request->is('api/leaderboard')) {
            return response()->json(['error' => __('Forbidden')], 403);
        }

        return $next($request);
    }
}
