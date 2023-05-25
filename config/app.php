<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   app.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

return [

    'version' => '1.19.1',

    

    'name' => env('APP_NAME', 'Stake'),

    

    'logo' => env('APP_LOGO', '/images/logo/logo.png'),

    

    'env' => env('APP_ENV', 'production'),

    

    'debug' => (bool) env('APP_DEBUG', false),

    

    'url' => env('APP_URL', ''),

    'asset_url' => env('ASSET_URL', null),

    'force_ssl' => env('FORCE_SSL', FALSE),

    

    'timezone' => 'UTC',

    

    'locale' => env('LOCALE', 'en'),

    
    
    'default_locale' => env('LOCALE', 'en'),

    'detect_browser_locale' => env('DETECT_BROWSER_LOCALE', TRUE),

    'locales' => [
        'en' => [
            'flag'  => 'us',
            'title' => 'English'
        ],
        'ru' => [
            'title' => 'Русский'
        ],
        'de' => [
            'title' => 'Deutsch',
        ],
        'es' => [
            'title' => 'Español',
        ],
        'fr' => [
            'title' => 'Français',
        ],
        'pt' => [
            'title' => 'Português',
        ],
        'nl' => [
            'title' => 'Nederlands',
        ],
        'cs' => [
            'flag'  => 'cz',
            'title' => 'Česky',
        ],
        'it' => [
            'title' => 'Italiano',
        ],
        'fi' => [
            'title' => 'Suomi',
        ],
        'sv' => [
            'flag'  => 'se',
            'title' => 'Svenska',
        ],
        'hu' => [
            'title' => 'Magyar',
        ],
        'el' => [
            'flag'  => 'gr',
            'title' => 'Ελληνικά',
        ],
        'da' => [
            'flag'  => 'dk',
            'title' => 'Dansk',
        ],
        'lv' => [
            'title' => 'Latviešu',
        ],
        'lt' => [
            'title' => 'Lietuvių',
        ],
        'et' => [
            'flag'  => 'ee',
            'title' => 'Eesti',
        ],
        'sk' => [
            'title' => 'Slovenčina',
        ],
        'sl' => [
            'flag'  => 'si',
            'title' => 'Slovenščina',
        ],
        'ko' => [
            'flag'  => 'kr',
            'title' => '한국어',
        ],
        
        'zh-cn' => [
            'flag'  => 'cn',
            'title' => '简体',
        ],
        
        'zh-tw' => [
            'flag'  => 'tw',
            'title' => '繁体',
        ],
        'ja' => [
            'flag'  => 'jp',
            'title' => '日本語',
        ],
    ],

    'translation_files_folder' => env('TRANSLATION_FILES_FOLDER', 'lang'),

    

    'fallback_locale' => 'en',

    

    'faker_locale' => 'en_US',

    

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'hash' => 'da34b0aa975fddd5a90a1eb2bb8cb9d2',

    'hashb' => '0dcd235baec3bed9c8374828935afa0d',

    

    'api' => [
        'releases' => [
            'base_url' => env('API_RELEASES_BASE_URL', 'https://stake.financialplugins.com/api/')
        ],
        'products' => [
            'base_url' => env('API_PRODUCTS_BASE_URL', 'https://financialplugins.com/api/')
        ],
    ],

    

    'providers' => [

        
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        
        SocialiteProviders\Manager\ServiceProvider::class,

        
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Http' => Illuminate\Support\Facades\Http::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,

    ],
];
