<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   DatabaseSeeder.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace Database\Seeders;

use App\Helpers\PackageManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    
    public function run(PackageManager $packageManager)
    {
        $this->call([AssetSeeder::class]);

        
        
        foreach ($packageManager->getInstalled() as $package) {
            $seederFile = sprintf('%sPackageSeeder.php', Str::of($package->id)->title()->replace('-', ''));

            if (file_exists(base_path(sprintf('packages/%s/database/seeders/%s', $package->base_path, $seederFile)))) {
                $this->call(str_replace('.php', '', $seederFile));
            }
        }
    }
}
