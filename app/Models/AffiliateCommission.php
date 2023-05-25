<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AffiliateCommission.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use App\Models\Scopes\PeriodScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;
    use PeriodScope;

    const TYPE_SIGN_UP = 1;
    const TYPE_GAME_LOSS = 2;
    const TYPE_GAME_WIN = 3;
    const TYPE_DEPOSIT = 4;
    const TYPE_RAFFLE_TICKET = 5;

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;

    
    protected $appends = [
        'title',
        'status_title',
        'is_pending',
        'created_ago'
    ];

    
    protected $casts = [
        'amount' => 'float',
        'commissions_total' => 'float'
    ];

    
    protected $fillable = [
        'status'
    ];

    public function commissionable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function referralAccount()
    {
        return $this->belongsTo(Account::class);
    }

    
    public function scopePending($query): Builder
    {
        return $query->where('status', '=', self::STATUS_PENDING);
    }

    
    public function scopeApproved($query): Builder
    {
        return $query->where('status', '=', self::STATUS_APPROVED);
    }

    
    public function scopeRejected($query): Builder
    {
        return $query->where('status', '=', self::STATUS_REJECTED);
    }

    public function getTitleAttribute()
    {
        switch ($this->type) {
            case self::TYPE_SIGN_UP:
                return __('Sign up');
                break;

            case self::TYPE_GAME_LOSS:
                return __('Game loss');
                break;

            case self::TYPE_GAME_WIN:
                return __('Game win');
                break;

            case self::TYPE_DEPOSIT:
                return __('Deposit');
                break;

            default:
                return __('Commission');
        }
    }

    public function getStatusTitleAttribute()
    {
        if ($this->status == self::STATUS_PENDING) {
           return __('Pending');
        } elseif ($this->status == self::STATUS_APPROVED) {
            return __('Approved');
        } elseif ($this->status == self::STATUS_REJECTED) {
            return __('Rejected');
        }

        return __('Unknown');
    }

    
    public function getIsPendingAttribute()
    {
        return $this->status == self::STATUS_PENDING;
    }
}
