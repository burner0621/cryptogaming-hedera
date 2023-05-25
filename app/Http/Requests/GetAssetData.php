<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GetAssetData.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAssetData extends FormRequest
{
    
    public function authorize()
    {
        return $this->asset->is_active;
    }

    
    public function rules()
    {
        return [
            
        ];
    }
}
