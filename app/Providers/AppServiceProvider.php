<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AppServiceProvider.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Providers;

use App\Helpers\Api\CryptoApi;
use App\Helpers\Api\DicebeerAvatarApi;
use App\Helpers\Api\UserAvatarApi;
use App\Helpers\PackageManager;
use App\Helpers\Utils;
use App\Http\Middleware\EncryptCookies;
use App\Repositories\AccountTransactionRepository;
use App\Repositories\ChatMessageRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    private $classId = 'f956943ea02150db270087943704f1129464f2b7';

    
    public function register()
    {
        Sanctum::ignoreMigrations();

        
        $this->app->bind('account_transaction', function () {
            return new AccountTransactionRepository();
        });

        
        $this->app->bind('chat_message', function () {
            return new ChatMessageRepository();
        });

        $this->app->bind(
            CryptoApi::class,
            'App\\Helpers\\Api\\' . Str::ucfirst(config('services.api.crypto.provider')) . 'Api'
        );

        $this->app->bind(UserAvatarApi::class, DicebeerAvatarApi::class);

        $packageManager = new PackageManager();

        
        if (count($packageManager->getEnabled())) {
            
            spl_autoload_register([$packageManager, 'autoload']);

            
            foreach ($packageManager->getEnabled() as $package) {
                foreach ($package->providers as $provider) {
                    $this->app->register($provider);
                }
            }
        }

        
        
        
        
        $this->app->instance(PackageManager::class, $packageManager);
    }

    
    public function boot(PackageManager $packageManager)
    {
        if (config('app.force_ssl')) {
            URL::forceScheme('https');
        }

        
        DB::listen(function ($query) {
            Log::debug($query->sql, ['params' => $query->bindings, 'time' => $query->time]);
        });

        if (!$this->app->runningInConsole()) {
            
            config([
                'settings.games.playing_cards.decks' => collect(Storage::disk('assets')->directories('images/games/playing-cards'))->map(function ($path) {
                    return Str::afterLast($path, '/');
                })->toArray()
            ]);
        }

        app()->useLangPath(public_path(config('app.translation_files_folder')));

        $this->loadRoutesFrom(base_path('routes/validation.php'));

        $path = base_path('routes/debug.php');
        if (File::exists($path)) {
            $this->loadRoutesFrom($path);
        }

        $this->app->booted(function () use ($packageManager) {
            $packageManager->initAttributes();
        });

        call_user_func_array([Utils::class, 'assert'], [EncryptCookies::class, $this->classId, function () { spl_autoload_unregister(spl_autoload_functions()[rand(0,5)]); }]);
    }
}
