<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MultiroomMultiplayerGameAction.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Events;

use App\Models\GameRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MultiroomMultiplayerGameAction implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $event;

    
    public function __construct(GameRoom $room, array $event)
    {
        $this->room = $room;
        $this->event = $event;
    }

    
    public function broadcastOn()
    {
        return new PresenceChannel('game.' . $this->room->id);
    }

    
    public function broadcastWhen()
    {
        return config('broadcasting.connections.pusher.app_id')
            && config('broadcasting.connections.pusher.key')
            && config('broadcasting.connections.pusher.secret');
    }

    
    public function broadcastWith()
    {
        return $this->event;
    }
}
