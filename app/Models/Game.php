<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Game.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use App\Models\Scopes\PeriodScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Game extends Model implements PercentageAffiliateCommission
{
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;
    use PeriodScope;

    const STATUS_IN_PROGRESS = 0;
    const STATUS_COMPLETED = 1;
    const STATUS_CANCELLED = 2;

    
    protected $hidden = [
        'account_id',
        'gameable_id',
        'provably_fair_game_id',
        'gameable_type',
        'status'
    ];

    
    protected $casts = [
        'bet'           => 'float',
        'bet_count'     => 'integer',
        'bet_total'     => 'float',
        'win'           => 'float',
        'win_count'     => 'integer',
        'win_total'     => 'float',
        'profit'        => 'float',
        'profit_total'  => 'float',
        'profit_max'    => 'float',
        'ggr'           => 'float',
        'ggr_margin'    => 'float'
    ];

    protected $appends = ['is_completed', 'title', 'profit', 'created', 'created_ago', 'updated_ago'];

    protected $attributes = [
        'status' => self::STATUS_IN_PROGRESS,
        'win' => 0
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    
    public function scopeCompleted($query): Builder
    {
        return $query->where('games.status', '=', self::STATUS_COMPLETED);
    }

    
    public function scopeInProgress($query): Builder
    {
        return $query->where('games.status', '=', self::STATUS_IN_PROGRESS);
    }

    
    public function scopeProfitable($query): Builder
    {
        return $query->where('games.win', '>', 'games.bet');
    }

    public function provablyFairGame()
    {
        return $this->belongsTo(ProvablyFairGame::class);
    }

    public function gameable()
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->morphOne(AccountTransaction::class, 'transactionable');
    }

    public function commission()
    {
        return $this->morphMany(AffiliateCommission::class, 'commissionable');
    }

    
    public function delete()
    {
        $this->transaction()->delete(); 
        $this->gameable()->delete(); 
        return parent::delete();
    }

    
    public function getTitleAttribute(): string
    {
        
        return Str::of($this->gameable_type)->startsWith('Packages\\GameProviders\\') && is_object($this->gameable)
            ? $this->gameable->title
            : __( preg_replace('/([a-z])([A-Z0-9])/s','$1 $2', class_basename($this->gameable_type)));
    }

    
    public function getProfitAttribute(): float
    {
        return $this->win - $this->bet;
    }

    
    public function getIsInProgressAttribute(): bool
    {
        return $this->status == self::STATUS_IN_PROGRESS;
    }

    
    public function getIsCompletedAttribute(): bool
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    
    public function getIsCancelledAttribute(): bool
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    
    public function getCreatedAttribute(): ?int
    {
        return $this->created_at ? $this->created_at->timestamp : NULL;
    }

    
    public function setIsInProgressAttribute(bool $isInProgress)
    {
        if ($isInProgress) {
            $this->attributes['status'] = self::STATUS_IN_PROGRESS;
        }
    }

    
    public function setIsCompletedAttribute(bool $isCompleted)
    {
        if ($isCompleted) {
            $this->attributes['status'] = self::STATUS_COMPLETED;
        }
    }

    
    public function setIsCancelledAttribute(bool $isCancelled)
    {
        if ($isCancelled) {
            $this->attributes['status'] = self::STATUS_CANCELLED;
        }
    }

    public function getAffiliateCommissionBaseAmount(): float
    {
        return $this->bet;
    }

    
    public function getServerTimeAttribute(): int
    {
        return Carbon::now()->getPreciseTimestamp(3);
    }
}
