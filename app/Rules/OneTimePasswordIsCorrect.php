<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   OneTimePasswordIsCorrect.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use PragmaRX\Google2FA\Google2FA;

class OneTimePasswordIsCorrect implements Rule
{
    private $secret;

    
    public function __construct(?string $secret)
    {
        $this->secret = $secret;
    }

    
    public function passes($attribute, $value)
    {
        $google2fa = new Google2FA();
        return $value && $this->secret && $google2fa->verifyKey($this->secret, $value);
    }

    
    public function message()
    {
        return __('Incorrect one-time password.');
    }
}
