<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   VerifyCsrfToken.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    
    protected $addHttpCookie = true;

    
    protected $except = [
        
    ];
}
