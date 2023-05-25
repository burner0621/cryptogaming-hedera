<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GetUser.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetUser extends FormRequest
{
    
    public function authorize()
    {
        
        return $this->user && ($this->user->is_active || $this->user()->is_admin);
    }

    
    public function rules()
    {
        return [
            
        ];
    }
}
