<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ConfigVariablesAreSet.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ConfigVariablesAreSet implements Rule
{
    private $vars = [];
    private $missingVars = [];

    
    public function __construct(array $vars)
    {
        $this->vars = $vars;
    }

    
    public function passes($attribute = NULL, $value = NULL)
    {
        
        foreach ($this->vars as $var) {
            $value = config($var);

            if ($value == '' || is_null($value)) {
                $this->missingVars[] = $var;
            }
        }

        return empty($this->missingVars);
    }

    
    public function message()
    {
        return __('Please check that the following configuration variables are set: :vars.', ['vars' => implode(', ', $this->missingVars)]);
    }
}
