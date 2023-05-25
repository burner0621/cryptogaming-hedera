<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   channels.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/








use App\Models\GameRoomPlayer;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('online', function ($user) {
    return $user ? $user->only('id', 'name', 'avatar', 'avatar_url') : FALSE;
});

Broadcast::channel('games', function ($user) {
    return TRUE;
});

Broadcast::channel('chat.{id}', function ($user, $chatRoomId) {
    return $user ? $user->only('id', 'name') : FALSE;
});


Broadcast::channel('multiplayer-game.{id}', function ($user, $packageId) {
    return $user->only('id', 'name', 'avatar', 'avatar_url');
});


Broadcast::channel('game.{id}', function ($user, $gameRoomId) {
    return GameRoomPlayer::where('game_room_id', $gameRoomId)->where('user_id', $user->id)->count() > 0
        ? $user->only('id', 'name', 'avatar', 'avatar_url')
        : FALSE;
});
