<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MultiplayerGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MultiplayerGame extends Model
{
    protected $dates = ['start_time', 'end_time'];

    protected $appends = ['start_time_unix', 'end_time_unix'];

    public function provablyFairGame()
    {
        return $this->belongsTo(ProvablyFairGame::class);
    }

    public function gameable()
    {
        return $this->morphTo();
    }

    
    public function setStartTimeAttribute(Carbon $date)
    {
        $this->attributes['start_time'] = $date->toDateTimeString('millisecond');
    }

    
    public function setEndTimeAttribute(Carbon $date)
    {
        $this->attributes['end_time'] = $date->toDateTimeString('millisecond');
    }

    
    public function getStartTimeUnixAttribute(): ?int
    {
        return $this->start_time ? $this->start_time->valueOf() : NULL;
    }

    
    public function getEndTimeUnixAttribute(): ?int
    {
        return $this->end_time ? $this->end_time->valueOf() : NULL;
    }

    
    public function getIsBettingInProgressAttribute(): bool
    {
        $now = Carbon::now();

        return $this->start_time
            && $this->end_time
            && $this->start_time->lte($now)
            && $this->end_time->gte($now);
    }
}
