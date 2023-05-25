<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MultiplayerGameAction.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class MultiplayerGameAction implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    
    public function broadcastOn()
    {
        return new PresenceChannel('multiplayer-game.' . $this->data['package_id']);
    }

    
    public function broadcastWhen()
    {
        return config('broadcasting.connections.pusher.app_id')
            && config('broadcasting.connections.pusher.key')
            && config('broadcasting.connections.pusher.secret');
    }

    
    public function broadcastWith()
    {
        return $this->data;
    }
}
