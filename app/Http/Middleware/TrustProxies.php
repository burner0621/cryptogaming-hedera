<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   TrustProxies.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    
    protected $proxies;

    
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
