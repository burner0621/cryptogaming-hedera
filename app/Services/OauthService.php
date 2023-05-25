<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   OauthService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;


class OauthService
{
    private $providers;

    public function __construct()
    {
        
        $this->providers = config('oauth');
    }

    
    public function getAll(): array
    {
        return $this->providers;
    }

    
    public function getEnabled(array $properties = []): array
    {
        $enabledProviders = array_filter($this->providers, function ($provider) {
            return $provider['client_id'] ?? FALSE
                && $provider['client_secret'] ?? FALSE
                && $provider['redirect'] ?? FALSE;
        });

        return empty($properties)
            ? $enabledProviders
            : array_map(function ($provider) use ($properties) {
                foreach ($provider as $property => $value) {
                    if (!in_array($property, $properties)) {
                        unset($provider[$property]);
                    }
                }
                return $provider;
            }, $enabledProviders);
    }
}
