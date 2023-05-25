<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ArtisanService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/


namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ArtisanService
{
    public static function migrateAndSeed()
    {
        
        Artisan::call('migrate', [
            '--force' => TRUE,
        ]);

        
        Artisan::call('db:seed', [
            '--force' => TRUE,
        ]);
    }

    public static function clearAllCaches()
    {
        
        Cache::flush();
        
        Artisan::call('config:clear');
        
        Artisan::call('cache:clear');
        
        Artisan::call('view:clear');
        
        Artisan::call('route:clear');
    }
}
