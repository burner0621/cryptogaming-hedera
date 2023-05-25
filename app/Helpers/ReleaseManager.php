<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ReleaseManager.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class ReleaseManager
{
    
    public function getInfo($forceRefresh = FALSE)
    {
        if ($forceRefresh) {
            Cache::forget('releases');
        }

        return Cache::remember('releases', 7200, function() { 
            $client = new Client(['base_uri' => config('app.api.releases.base_url')]);
            $response = $client->request('GET', 'releases');

            return json_decode($response->getBody()->getContents());
        });
    }
}
