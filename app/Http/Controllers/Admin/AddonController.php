<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AddonController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Admin;

use App\Helpers\PackageManager;
use App\Helpers\ReleaseManager;
use App\Http\Controllers\Controller;
use App\Services\ArtisanService;
use App\Services\DotEnvService;
use App\Services\LicenseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AddonController extends Controller
{
    public function index(PackageManager $packageManager, ReleaseManager $releaseManager)
    {
        $releases = $releaseManager->getInfo();

        $packages = collect($packageManager->getAll())
            ->each(function ($package) use ($releases) {
                $release = data_get($releases, 'add-ons.' . $package->id);
                $package->update_available = $release && $package->enabled && version_compare($package->version, $release->version, '<');
                $package->update_can_be_installed = $package->update_available && version_compare(config('app.version'), $release->min_app_version, '>=');
            });

        return compact('releases', 'packages');
    }

    public function get(PackageManager $packageManager, $packageId)
    {
        return [
            'code' => env($packageManager->getCodeVariable($packageId))
        ];
    }

    
    public function disable($packageId, PackageManager $packageManager)
    {
        $package = $packageManager->get($packageId);

        if (!$package) {
            return $this->errorResponse(__('Package ":id" does not exist.', ['id' => $packageId]));
        }

        try {
            if (Storage::disk('local')->put('packages/' . $packageId . '/disabled', '')) {
                return $this->successResponse(__('Add-on ":name" successfully disabled.', ['name' => $package->name]));
            } else {
                return $this->errorResponse(__('Could not disable the add-on. Please check that storage/app folder is writable.'));
            }
        } catch(Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    
    public function enable($packageId, PackageManager $packageManager)
    {
        $package = $packageManager->get($packageId);

        if (!$package) {
            return $this->errorResponse(__('Package ":id" does not exist.', ['id' => $packageId]));
        }

        try {
            if (Storage::disk('local')->delete('packages/' . $packageId . '/disabled')) {
                return $this->successResponse(__('Add-on ":name" successfully enabled.', ['name' => $package->name]));
            } else {
                return $this->errorResponse(__('Could not enable the add-on. Please check that storage/app folder is writable.'));
            }
        } catch(Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function install($packageId, Request $request, LicenseService $licenseService, PackageManager $packageManager, ReleaseManager $releaseManager, DotEnvService $dotEnvService)
    {
        set_time_limit(1800);

        $releases = $releaseManager->getInfo();
        $package = $packageManager->get($packageId);

        if (!$package) {
            return $this->errorResponse(__('Package ":id" does not exist.', ['id' => $packageId]));
        }

        if (!isset($package->hash) || !isset($releases->{'add-ons'}->{$package->id})) {
            return $this->errorResponse(__('Package ":id" can not be installed or upgraded.', ['id' => $packageId]));
        }

        $response = $licenseService->download($request->code, env(FP_EMAIL), $package->hash, $releases->{'add-ons'}->{$package->id}->version);

        
        if (!$response->success) {
            return $this->errorResponse($response->message);
        }

        
        $dotEnvService->save([ $packageManager->getCodeVariable($packageId) => $request->code, $packageManager->getHashVariable($packageId) => $response->message ]);

        
        try {
            $zipFileName = 'releases/' . $packageId . '.zip';
            $storage = Storage::disk('local');
            $storage->put($zipFileName, $response->content);

            
            $zip = new ZipArchive();
            if ($zip->open($storage->path($zipFileName)) === TRUE) {
                $zip->extractTo(base_path());
                $zip->close();
            }

            
            $storage->delete($zipFileName);
        } catch(Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        
        
        if (!$package->enabled) {
            $packageManager->load(base_path(sprintf('packages/%s/config.json', $package->id)));

            foreach ($package->providers as $provider) {
                app()->register($provider);
            }
        }

        
        ArtisanService::migrateAndSeed();
        
        ArtisanService::clearAllCaches();

        return $this->successResponse(__('Add-on ":name" successfully installed or updated. Please check the add-on documentation to see if there are any extra steps required.', ['name' => $package->name]));
    }

    public function changelog($packageId)
    {
        $path = base_path('packages/' . $packageId . '/CHANGELOG.txt');

        return ['changelog' => File::exists($path) ? File::get($path) : __('No changelog found.')];
    }

    public function registerBundle(Request $request, LicenseService $licenseService, PackageManager $packageManager, ReleaseManager $releaseManager, DotEnvService $dotEnvService)
    {
        $response = $licenseService->register($request->code, env(FP_EMAIL), NULL, TRUE);

        if (!$response->success) {
            return $this->errorResponse($response->message);
        }

        
        $dotEnvService->save(collect($response->message)
            ->filter(function ($c, $h) use ($packageManager) {
                return !!$packageManager->getByHash($h);
            })
            ->mapWithKeys(function ($c, $h) use ($packageManager) {
                return [$packageManager->getCodeVariable($packageManager->getByHash($h)->id) => $c];
            })->toArray()
        );

        return $this->successResponse(__('The bundle is successfully registered. Please install each add-on by clicking the "Install" button.'));
    }
}
