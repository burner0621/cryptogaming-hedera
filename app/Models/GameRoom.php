<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameRoom.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GameRoom extends Model
{
    const STATUS_OPEN  = 0;
    const STATUS_CLOSED = 1;

    
    protected $casts = [
        'parameters' => 'object'
    ];

    
    protected $hidden = [
        'status',
        'gameable_type'
    ];

    
    public function getIsOpenAttribute()
    {
        return $this->status == self::STATUS_OPEN;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public function players()
    {
        return $this->hasMany(GameRoomPlayer::class);
    }

    
    public function player(Model $model): ?GameRoomPlayer
    {
        $column = $model instanceof Game ? 'game_id' : 'user_id';

        return $this->players()->where($column, $model->id)->get()->first();
    }

    
    public function playerById(int $id, $column = 'user_id'): ?GameRoomPlayer
    {
        return $this->players()->where($column, $id)->get()->first();
    }

    
    public function activePlayers()
    {
        return $this->players()->whereNotNull('game_id');
    }

    public function scopeOpen($query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }
}
