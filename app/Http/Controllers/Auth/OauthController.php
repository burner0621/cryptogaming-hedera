<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   OauthController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Auth;

use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\SocialProfile;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Laravel\Socialite\Facades\Socialite;

class OauthController extends Controller
{
    public function __construct()
    {
        $this->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class
            ]);
    }

    public function url(Request $request, string $provider)
    {
        
        config(['services.' . $provider => config('oauth.' . $provider)]);

        $driver = Socialite::driver($provider);

        $url = $driver->stateless()->redirect()->getTargetUrl();

        info(sprintf('Redirect URL for %s: %s', $provider, $url));

        return compact('url');
    }

    public function callback(Request $request, $provider)
    {
        
        config(['services.' . $provider => config('oauth.' . $provider)]);

        
        try {
            $providerUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Throwable $e) {
            Log::error(sprintf('%s login error: %s', $provider, $e->getMessage()));
            Log::error($e->getTraceAsString());
            return redirect('login');
        }

        info('User: ' . json_encode($providerUser));

        
        $userEmail = $providerUser->getEmail() ?: $providerUser->getId() . '_' . $providerUser->getNickname();

        if (strpos($userEmail, '@') === FALSE) {
            $userEmail .= '@' . $provider . '.com';
        }

        $userName = $providerUser->getName() ?: ($providerUser->getNickname() ?: $providerUser->getId());

        
        if (User::where('name', $userName)->count()) {
            $userName .= ' ' . random_int(1000, 99999999);
        }

        $user = User::where('email', $userEmail)->first();

        if (!$user) {
            $user = UserRepository::create([
                'name'              => $userName,
                'email'             => $userEmail,
                'avatar'            => $providerUser->getAvatar(),
                'password'          => $providerUser->token,
                'email_verified_at' => Carbon::now(),
            ]);
        }

        
        if ($user->wasRecentlyCreated) {
            event(new Registered($user));
        }

        
        SocialProfile::firstOrCreate(
            ['provider_name' => $provider, 'provider_user_id' => $providerUser->getId()],
            ['user_id' => $user->id]
        );

        
        auth()->guard()->login($user, TRUE);

        return redirect('/');
    }
}
