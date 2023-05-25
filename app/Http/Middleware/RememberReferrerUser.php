<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   RememberReferrerUser.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;

class RememberReferrerUser
{
    
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        
        if ($request->is('/') && !$request->hasCookie('ref') && $request->query('ref') ) {
            
            $response->cookie('ref', $request->query('ref'), 525600);
        }

        return $response;
    }
}
