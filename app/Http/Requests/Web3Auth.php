<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Web3Auth.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Web3Auth extends FormRequest
{
    
    public function authorize()
    {
        return $this->provider
            && array_key_exists($this->provider, config('auth.web3'))
            && config('auth.web3.' . $this->provider . '.enabled');
    }

    
    public function rules()
    {
        return [
            'signature' => 'required|string|min:20',
            'address' => 'required|string|min:20',
        ];
    }
}
