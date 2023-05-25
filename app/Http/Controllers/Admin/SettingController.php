<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   SettingController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Admin;

use App\Helpers\PackageManager;
use App\Http\Controllers\Controller;
use App\Services\DotEnvService;
use Database\Seeders\AssetSeeder;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    public function get(PackageManager $packageManager)
    {
        $config = collect(['app', 'auth', 'broadcasting', 'database', 'logging', 'mail', 'oauth', 'queue', 'settings', 'services', 'session'])
            ->mapWithKeys(function ($item, $key) {
                
                return [$item => config($item)];
            });

        foreach ($packageManager->getEnabled() as $id => $package) {
            $config->put($id, config($id));
        }

        return response()->json(['config' => $config])->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function update(Request $request, DotEnvService $dotEnvService)
    {
        
        return $dotEnvService->save($request->all())
            ? $this->successResponse(__('Settings successfully saved.'))
            : $this->errorResponse(__('There was an error while saving the settings.') . ' ' . __('Please check that .env file exists and has write permissions.'));
    }

    public function runAssetSeeder()
    {
        Artisan::call(SeedCommand::class, ['--class' => AssetSeeder::class, '--force' => TRUE]);

        return $this->successResponse();
    }
}
