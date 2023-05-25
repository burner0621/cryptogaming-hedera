<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PageController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers;

use App\Helpers\PackageManager;
use App\Models\Game;
use App\Services\OauthService;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index(Request $request, OauthService $OAuthService, PackageManager $packageManager, string $path = NULL)
    {
        $variables = [
            'config' => array_merge(
                $this->mapConfigVariables('app', ['name', 'version', 'logo', 'banner', 'url', 'locale', 'default_locale', 'translation_files_folder']),
                $this->mapConfigVariables('broadcasting', ['connections.pusher.key', 'connections.pusher.options.cluster']),
                $this->mapConfigVariables('settings', ['theme', 'interface', 'content', 'format', 'games', 'bonuses', 'tips', 'affiliate', 'users']),
                $this->mapConfigVariables('services', ['recaptcha.public_key']),
                ['auth' => [
                    'web3' => collect(config('auth.web3'))
                        ->filter(function ($provider) {
                            return $provider['enabled'];
                        })
                    ]
                ],
                ['oauth' => $OAuthService->getEnabled(['client_id', 'mdi'])],
            ),
            'user' => $request->user() ? UserRepository::load($request->user()) : NULL,
            'games' => [
                'count' => Game::completed()->count(),
                'biggest_win' => Cache::remember('games.biggest_win', 3600, function () {
                    return tap(
                        Game::with('account:id,user_id', 'account.user:id,name,email,avatar') 
                            ->completed()
                            ->profitable()
                            ->orderBy('win', 'desc')
                            ->limit(1)
                            ->first(),
                        function (?Game $game) {
                            if ($game) {
                                $game->account->user->makeHidden(['email']);
                            }
                        });
                    }),
                'last_win' => tap(
                    Game::with('account:id,user_id','account.user:id,name,email,avatar') 
                        ->completed()
                        ->profitable()
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->first(),
                    function (?Game $game) {
                        if ($game) {
                            $game->account->user->makeHidden(['email']);
                        }
                    })
            ],
            'assets' => [
                'deck' =>  config('settings.games.playing_cards.deck')
                    ? collect(Storage::disk('assets')->files(sprintf('images/games/playing-cards/%s', config('settings.games.playing_cards.deck'))))
                        ->map(function ($path) {
                            return url($path);
                        })
                        ->toArray()
                    : []
            ],
            'stylesheets' => collect(config('settings.theme.fonts'))
                ->map(function ($font) {
                    return $font['url'];
                })
                ->unique()
        ];

        
        $variables['config']['app']['locales'] = collect(config('app.locales'))
            ->filter(function ($locale, $code) {
                return Storage::disk('resources')->exists('lang/' . $code . '.json');
            })
            ->map(function ($locale, $code) {
                return array_merge(['code' => $code], $locale);
            })
            ->sortBy('code');

        
        
        $namedRoutes = Route::getRoutes()->getRoutesByName();

        $variables['routes'] = array_combine(
            array_keys($namedRoutes),
            array_map(function ($route) {
                return '/' . $route->uri;
            }, $namedRoutes)
        );

        
        $enabledPackages = $packageManager->getEnabled();

        $variables['packages'] = array_combine(
            array_keys($enabledPackages),
            array_map(function ($package) {
                return [
                    'type' => $package->type,
                    'name' => __($package->name)
                ];
            }, $enabledPackages)
        );

        
        foreach ($packageManager->getEnabled() as $package) {
            $packageConfig = [];

            
            if ($publicVariables = config($package->id . '.public_variables')) {
                collect($publicVariables)
                    ->push('live')
                    ->each(function ($key) use ($package, &$packageConfig) {
                        
                        data_fill($packageConfig, $key, config($package->id . '.' . $key));
                    });
            } else {
                $packageConfig = config($package->id);
            }

            
            if (isset($packageConfig['variations'])) {
                collect($packageConfig['variations'])->each(function ($variation) {
                    $variation->_title = __($variation->title);
                });
            }

            $variables['config'][$package->id] = $packageConfig;
        }

        return view('index', $variables);
    }

    public function show(string $page)
    {
        $file = 'html/' . preg_replace('#[^a-z0-9-_]#i', '', $page) . '.html';

        $html = Storage::disk('public')->exists($file)
            ? Storage::disk('public')->get($file)
            : (Storage::disk('assets')->exists($file)
                ? Storage::disk('assets')->get($file)
                : NULL);

        return response()->json([
            'html' => preg_replace_callback(
                "#{{config\('([a-z0-9._]+)'\)}}#is",
                function($matches) {
                    return config($matches[1]);
                },
                $html
            )
        ]);
    }

    public function recentGames()
    {
        return Game::completed()
            ->orderBy('created_at', 'desc')
            ->take(10)
            
            ->with('account:id,user_id', 'account.user:id,name,email,avatar') 
            ->get()
            ->map(function (Game $game) {
                $game->account->user->makeHidden(['email']);
                return $game;
            });
    }

    
    protected function mapConfigVariables ($key, $array)
    {
        $result = [];

        foreach ($array as $item) {
            data_set($result, $key . '.' . $item, data_get(config($key), $item));
        }

        return $result;
    }
}
