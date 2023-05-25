<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ChatController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers;

use App\Facades\ChatMessage as ChatMessageFacade;
use App\Http\Requests\SendChatMessage;
use App\Models\ChatMessage;
use App\Models\ChatRoom;

class ChatController extends Controller
{
    
    public function getRooms()
    {
        return ChatRoom::select('id', 'name')
            ->enabled()
            ->orderBy('id')
            ->get()
            ->map
            ->setAppends([]);
    }

    
    public function getMessages(ChatRoom $room)
    {
        return ChatMessage::fromRoom($room->id)
            ->orderBy('id', 'desc')
            ->with('user:id,name,email,avatar')
            ->with(['recipients' => function($query) {
                $query->select('user_id AS id', 'name', 'avatar'); 
            }])
            ->limit(100)
            ->get()
            ->map(function ($room) {
                $room->user->setAppends(['avatar_url']);
                $room->user->makeHidden(['email']);
                return $room;
            })
            ->reverse()
            ->values(); 
    }

    
    public function sendMessage(SendChatMessage $request, ChatRoom $room)
    {
        ChatMessageFacade::create($room, $request->user(), $request->message, array_unique($request->recipients));

        return [
            'success' => TRUE
        ];
    }
}
