<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ChatRoom.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;

    
    protected $appends = ['status_title', 'created_ago', 'updated_ago'];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }

    
    public function scopeEnabled($query): Builder
    {
        return $query->where('enabled', TRUE);
    }

    public function getStatusTitleAttribute()
    {
        return $this->enabled
            ? __('Enabled')
            : __('Disabled');
    }
}
