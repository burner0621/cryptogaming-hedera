<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LogResponse.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogResponse
{
    
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        info(json_encode([
            'action'    => $request->route()->getActionName(),
            'code'      => $response->getStatusCode(),
            'content'   => $response instanceof JsonResponse ? json_decode($response->getContent()) : $response->getContent(),
        ], JSON_PRETTY_PRINT));

        return $response;
    }
}
