<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GetMultiplayerGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Helpers\PackageManager;
use Illuminate\Foundation\Http\FormRequest;

class GetMultiplayerGame extends FormRequest
{
    
    public function authorize(PackageManager $packageManager)
    {
        $package = $packageManager->get($this->packageId);

        return optional($package)->enabled;
    }

    
    public function rules()
    {
        return [
            
        ];
    }
}
