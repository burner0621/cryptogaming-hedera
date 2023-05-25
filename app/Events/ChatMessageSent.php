<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ChatMessageSent.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $message;

    
    public function __construct(ChatRoom $room, ChatMessage $message)
    {
        $this->room = $room;
        $this->message = $message->makeHidden($message->getHidden());
    }

    
    public function broadcastOn()
    {
        return new PresenceChannel('chat.' . $this->room->id);
    }

    
    public function broadcastWhen()
    {
        return config('broadcasting.connections.pusher.app_id')
            && config('broadcasting.connections.pusher.key')
            && config('broadcasting.connections.pusher.secret');
    }

    
    public function broadcastWith()
    {
        return array_merge(
            $this->message->only('message', 'created_at', 'created_ago'),
            ['user' => $this->message->user->only('id', 'name', 'avatar_url')],
            [
                'recipients' => $this->message->recipients
                    ? $this->message->recipients->map->only('id', 'name', 'avatar_url')
                    : []
            ]
        );
    }
}
