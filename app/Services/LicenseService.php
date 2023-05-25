<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LicenseService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;

use GuzzleHttp\Client;

class LicenseService
{
    public function register($code, $email, $hash = NULL, $bundle = FALSE)
    {
        try {
            $client = new Client(['base_uri' => config('app.api.products.base_url')]);

            $response = $client->request('POST', sprintf('licenses/register%s', $bundle ? '-bundle' : ''), [
                'form_params' => [
                    'code' => $code,
                    'email' => $email,
                    'domain' => request()->getHost(),
                    'hash' => $bundle ? config('app.hashb') : ($hash ?: config('app.hash'))
                ]
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (\Throwable $e) {
            return (object) ['success' => FALSE, 'message' => $e->getMessage()];
        }
    }

    public function download($code, $email, $hash, $version)
    {
        try {
            $client = new Client(['base_uri' => config('app.api.products.base_url')]);

            $response = $client->request('POST', 'products/download', [
                'form_params' => [
                    'code' => $code,
                    'email' => $email,
                    'domain' => request()->getHost(),
                    'hash' => $hash,
                    'version' => $version
                ],
            ]);

            return $response->getHeaderLine('Content-Type') == 'application/zip'
                ? (object) ['success' => TRUE, 'message' => $response->getHeaderLine('Security-Hash'), 'content' => $response->getBody()->getContents()]
                : json_decode($response->getBody()->getContents());
        } catch (\Throwable $e) {
            return (object) ['success' => FALSE, 'message' => $e->getMessage()];
        }
    }
}
