<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   BlockConcurrentRequests.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlockConcurrentRequests
{
    private $cacheKey;

    public function __construct(Request $request)
    {
        $this->cacheKey = 'users.' . $request->user()->id . '.requests_count';
    }

    
    public function handle($request, Closure $next)
    {
        $count = Cache::get($this->cacheKey, 0);
        Cache::put($this->cacheKey, ++$count, 30);

        
        if ($count > 1) {
            abort(429);
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        $count = Cache::get($this->cacheKey, 0);
        Cache::put($this->cacheKey, --$count, 30);
    }
}
