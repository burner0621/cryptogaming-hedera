<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   UserNotJoinedGameRoom.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Rules;

use App\Models\GameRoomPlayer;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UserNotJoinedGameRoom implements Rule
{
    private $user;

    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    
    public function passes($attribute, $value)
    {
        return GameRoomPlayer::where('user_id', $this->user->id)->count() == 0;
    }

    
    public function message()
    {
        return __('You can not join more than one game room at the same time.');
    }
}
