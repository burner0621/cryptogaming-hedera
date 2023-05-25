<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PhpExtensionIsInstalled.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhpExtensionIsInstalled implements Rule
{
    private $extension;

    
    public function __construct(string $extension)
    {
        $this->extension = $extension;
    }

    
    public function passes($attribute = NULL, $value = NULL)
    {
        return extension_loaded($this->extension);
    }

    
    public function message()
    {
        return __('PHP extension ":ext" should be installed and enabled on your server.', ['ext' => $this->extension]);
    }
}
