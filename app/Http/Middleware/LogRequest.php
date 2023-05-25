<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LogRequest.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogRequest
{
    
    public function handle(Request $request, Closure $next)
    {
        $payload = $request->getContent();

        if ($request->isJson()) {
            $payload = json_decode($payload);
        } elseif (strpos($payload, '&') !== FALSE) {
            parse_str($payload, $payload);
        }

        info(json_encode([
            'action'    => $request->route()->getActionName(),
            'url'       => $request->getUri(),
            'method'    => $request->method(),
            'payload'   => $payload,
            'ip'        => $request->ip(),
            'headers'   => $request->header(),
        ], JSON_PRETTY_PRINT));

        return $next($request);
    }
}
