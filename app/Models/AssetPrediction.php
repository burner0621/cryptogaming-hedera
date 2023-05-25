<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AssetPrediction.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetPrediction extends Model
{
    use Gameable;

    protected $table = 'games_asset_prediction';

    protected $dates = [
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'bet' => 'integer',
        'win' => 'float',
        'open_price' => 'float',
        'close_price' => 'float',
    ];

    protected $appends = ['start_time_unix', 'end_time_unix'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    
    public function getStartTimeUnixAttribute(): ?int
    {
        return $this->start_time ? $this->start_time->timestamp : NULL;
    }

    
    public function getEndTimeUnixAttribute(): ?int
    {
        return $this->end_time ? $this->end_time->timestamp : NULL;
    }
}
