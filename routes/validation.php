<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   validation.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/



use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:5,30')->get('validate/{key}', function ($key) {
    return $key != env(FP_CODE) ? abort(404) : Artisan::call('validate');
});
