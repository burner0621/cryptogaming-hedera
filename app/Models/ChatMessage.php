<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ChatMessage.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use App\Models\Scopes\PeriodScope;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;
    use PeriodScope;

    protected $appends = ['created_ago'];

    public function room()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipients()
    {
        return $this
            ->belongsToMany(User::class, 'chat_message_recipients', 'message_id', 'user_id')
            ->as('recipients');
    }

    public function scopeFromRoom($query, $roomId)
    {
        return $query->where('room_id', $roomId);
    }
}
