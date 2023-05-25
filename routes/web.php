<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   web.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('{path}', [PageController::class, 'index'])->where('path', '.*')->middleware('referrer');
