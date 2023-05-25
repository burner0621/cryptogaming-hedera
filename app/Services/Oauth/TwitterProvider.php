<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   TwitterProvider.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services\Oauth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Laravel\Socialite\Two\TwitterProvider as TwitterProviderOauth2;
use SocialiteProviders\Manager\ConfigTrait;

class TwitterProvider extends TwitterProviderOauth2
{
    use ConfigTrait;

    protected function getCacheKey(): string
    {
        return sha1(collect([
                'twitter',
                url('/'),
                $this->request->header('User-Agent'),
                $this->request->ip(),
            ])
            ->implode('|'));
    }

    public function redirect()
    {
        $state = null;

        
        if ($this->usesPKCE()) {
            Cache::put($this->getCacheKey(), $this->getCodeVerifier(), now()->addMinute());
        }

        return new RedirectResponse($this->getAuthUrl($state));
    }

    protected function getCodeChallenge()
    {
        $hashed = hash('sha256', Cache::get($this->getCacheKey()), TRUE);

        return rtrim(strtr(base64_encode($hashed), '+/', '-_'), '=');
    }

    protected function getTokenFields($code)
    {
        $fields = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
        ];

        if ($this->usesPKCE()) {
            $fields['code_verifier'] = Cache::pull($this->getCacheKey());
        }

        return $fields;
    }

}
