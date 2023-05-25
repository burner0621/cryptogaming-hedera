<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PackageManager.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class PackageManager
{
    protected $packages = [];
    protected $packagesCollection;
    protected $appDirectory;

    function __construct()
    {
        $this->appDirectory = __DIR__ . '/../../';

        
        foreach (glob($this->appDirectory . 'packages/*/config.json') as $configFile) {
            $this->load($configFile);
        }

        $this->packagesCollection = collect($this->packages);

        return $this;
    }

    
    public function load(string $configFile): PackageManager
    {
        if ($package = json_decode(file_get_contents($configFile))) {
            $this->packages[$package->id] = $package;
            $this->packages[$package->id]->code = env($this->getCodeVariable($package->id));
            $this->packages[$package->id]->code_required = in_array($package->type, ['game', 'add-on', 'prediction', 'provider']) && $package->id != 'baccarat';
            $this->packages[$package->id]->type = $package->type;
            $this->packages[$package->id]->model = sprintf('%sModels\\%s', $package->namespace, Str::studly($package->id));
            $this->packages[$package->id]->installed = $this->installed($package->id);
            $this->packages[$package->id]->enabled = $this->enabled($package->id);
            $this->packages[$package->id]->min_app_version_installed = version_compare(config('app.version'), $package->min_app_version, '>=');
        }

        return $this;
    }

    
    public function initAttributes(): PackageManager
    {
        foreach ($this->getEnabled() as $package) {
            $package->version = config($package->id . '.version');
            $package->name = __($package->name);
        }

        return $this;
    }

    
    public function get($id)
    {
        return $this->packages[$id] ?? NULL;
    }

    public function getByHash(string $hash)
    {
        return $this->packagesCollection->where('hash', $hash)->first();
    }

    
    public function getPackageProperty($id, $property)
    {
        $package = $this->get($id);
        return $package ? ($package->{$property} ?? NULL) : NULL;
    }

    
    public function getAll(): array
    {
        return $this->packages;
    }


    
    public function getInstalled(): array
    {
        return array_filter($this->packages, function($package) {
            return $package->installed;
        });
    }

    
    public function getEnabled(): array
    {
        return array_filter($this->packages, function($package) {
            return $package->enabled;
        });
    }

    
    public function installed($id): bool
    {
        $package = $this->get($id);
        return $package && file_exists($this->appDirectory . 'packages/' . $package->base_path . '/' . $package->source_path . '/' . str_replace([$package->namespace, '\\'], ['','/'], $package->providers[0]) . '.php');
    }

    
    public function enabled($id): bool
    {
        $package = $this->get($id);

        return $package && $this->installed($id) &&
            isset($package->min_app_version) &&
            version_compare(config('app.version'), $package->min_app_version, '>=') &&
            !Storage::disk('local')->exists(sprintf('packages/%s/disabled', $id)) &&
            (env($this->getCodeVariable($id)) || !$package->code_required);
    }

    
    public function disabled($id): bool
    {
        return !$this->enabled($id);
    }

    
    public function getPackageIdByClass($class): string
    {
        
        return preg_replace('#([0-9]+)-#', '-$1', Str::kebab(class_basename($class)));
    }

    public function getCodeVariable($id): string
    {
        return Str::of($id)->upper()->replace('-', '_')->append('_')->append(constant("\x46\x50\x5f\x43\x4f\x44\x45"));
    }

    public function getHashVariable($id): string
    {
        return Str::of($id)->upper()->replace('-', '_')->append('_')->append(constant("\x46\x50\x5f\x48\x41\x53\x48"));
    }

    
    public function autoload($className)
    {
        foreach ($this->getInstalled() as $package) {
            
            $static = (array) $package->static;

            
            if (in_array($className, array_keys($static))) {
                include_once base_path('packages/' . $package->base_path . '/' . $static[$className] . '/' . $className . '.php');
            
            } elseif (strpos($className, $package->namespace) !== FALSE) {
                include_once base_path('packages/' . $package->base_path . '/' . $package->source_path . '/' . str_replace([$package->namespace, '\\'], ['','/'], $className) . '.php');
            }
        }
    }
}
