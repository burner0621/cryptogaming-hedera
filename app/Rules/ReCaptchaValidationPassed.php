<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ReCaptchaValidationPassed.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Rules;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class ReCaptchaValidationPassed implements Rule
{
    
    public function passes($attribute, $value)
    {
        
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/'
        ]);
        $response = $client->post('siteverify', [
            'query' => [
                'secret'    => config('services.recaptcha.secret_key'),
                'response'  => $value,
                'remoteip'  => request()->ip(),
            ]
        ]);

        info($response->getBody());

        $responseBody = json_decode($response->getBody());

        return $responseBody->success ?? FALSE;
    }

    
    public function message()
    {
        return __('Please verify that you are a human.');
    }
}
