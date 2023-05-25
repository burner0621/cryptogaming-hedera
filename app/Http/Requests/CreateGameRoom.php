<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CreateGameRoom.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Helpers\PackageManager;
use App\Rules\MaxOpenGameRoomsLimit;
use App\Rules\UserNotJoinedGameRoom;
use Illuminate\Foundation\Http\FormRequest;

class CreateGameRoom extends FormRequest
{
    
    public function authorize(PackageManager $packageManager)
    {
        $package = $packageManager->get($this->packageId);

        return !!$package && $package->enabled;
    }

    
    public function rules()
    {
        $rules = [
            'name' => [
                'required',
                'min:3',
                'max:50',
                new MaxOpenGameRoomsLimit($this->user()),
                new UserNotJoinedGameRoom($this->user()) 
            ]
        ];

        
        foreach (config($this->packageId . '.parameters') as $parameter) {
            if ($parameter['validation']) {
                $rules['parameters.' . $parameter['id']] = $parameter['validation'];
            }
        }

        return $rules;
    }
}
