<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Handler.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    
    protected $dontReport = [
        
    ];

    
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
