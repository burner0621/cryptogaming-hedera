<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameRoomController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers;

use App\Helpers\PackageManager;
use App\Http\Requests\CreateGameRoom;
use App\Http\Requests\GetGameRooms;
use App\Http\Requests\JoinGameRoom;
use App\Http\Requests\LeaveGameRoom;
use App\Models\GameRoom;
use App\Models\GameRoomPlayer;
use App\Models\User;
use Illuminate\Http\Request;

class GameRoomController extends Controller
{
    public function index(GetGameRooms $request, $packageId, PackageManager $packageManager)
    {
        
        $rooms = GameRoom::where('gameable_type', $packageManager->get($packageId)->model)
            ->open()
            ->with('players')
            ->withCount('players')
            ->orderBy('id', 'desc')
            ->get();

        
        $room = $rooms
            ->filter(function ($room) use ($request) {
                return $room->player($request->user());
            })
            ->first();

        
        $game = $room
            ? $room->player($request->user())->game
            : NULL;

        return $room
            ? [
                'room' => $room,
                'game' => $game ? $game->loadMissing('gameable')->append('server_time') : NULL
            ]
            : [
                
                'rooms' => $rooms
                    ->filter(function ($room) {
                        return $room->players_count < $room->parameters->players_count;
                    })
                    ->values()
                    ->map
                    ->only(['id', 'name', 'parameters', 'players_count'])
            ];
    }

    
    public function create(CreateGameRoom $request, $packageId, PackageManager $packageManager)
    {
        $room = new GameRoom();
        $room->owner()->associate($request->user());
        $room->name = $request->name;
        $room->gameable_type = $packageManager->get($packageId)->model;
        $room->status = GameRoom::STATUS_OPEN;
        $room->parameters = $request->parameters;
        $room->save();

        return $this->joinGameRoom($room, $request->user());
    }

    
    public function join(JoinGameRoom $request, $packageId)
    {
        $room = GameRoom::find($request->room_id);

        return $this->joinGameRoom($room, $request->user());
    }

    
    public function leave(LeaveGameRoom $request, $packageId)
    {
        GameRoomPlayer::where('game_room_id', $request->room_id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return $this->successResponse();
    }

    private function joinGameRoom(GameRoom $room, User $user)
    {
        $player = new GameRoomPlayer();
        $player->room()->associate($room);
        $player->user()->associate($user);
        $player->save();

        return $this->successResponse(['room' => $room]);
    }
}
