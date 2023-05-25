<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   TrimStrings.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
