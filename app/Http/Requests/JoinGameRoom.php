<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   JoinGameRoom.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Helpers\PackageManager;
use App\Models\GameRoom;
use App\Rules\BalanceIsSufficient;
use App\Rules\GameRoomIsNotFull;
use App\Rules\UserNotJoinedGameRoom;
use Illuminate\Foundation\Http\FormRequest;

class JoinGameRoom extends FormRequest
{
    
    public function authorize(PackageManager $packageManager)
    {
        $package = $packageManager->get($this->packageId);

        return !!$package && $package->enabled;
    }

    
    public function rules()
    {
        $room = GameRoom::where('id', intval($this->room_id))->open()->firstOrFail();

        return [
            'room_id' => [
                new GameRoomIsNotFull($room), 
                new UserNotJoinedGameRoom($this->user()), 
                new BalanceIsSufficient($this->user(), $room->parameters->bet)
            ]
        ];
    }
}
