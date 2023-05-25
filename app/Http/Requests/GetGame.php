<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GetGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetGame extends FormRequest
{
    
    public function authorize()
    {
        return $this->game && $this->game->is_completed;
    }

    
    public function rules()
    {
        return [
            
        ];
    }
}
