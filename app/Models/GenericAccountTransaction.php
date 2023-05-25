<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GenericAccountTransaction.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use App\Models\Scopes\PeriodScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GenericAccountTransaction extends Model
{
    use StandardDateFormat;
    use PeriodScope;

    const TYPE_DEBIT = 1;
    const TYPE_CREDIT = 2;
    const TYPE_AFFILIATE_COMMISSION = 3;
    const TYPE_TIP = 4;

    
    protected $appends = [
        'title'
    ];

    
    protected $casts = [
        'amount' => 'float',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction()
    {
        return $this->morphOne(AccountTransaction::class, 'transactionable');
    }

    public function scopeDebits($query): Builder
    {
        return $query->where('type', self::TYPE_DEBIT);
    }

    public function scopeCredits($query): Builder
    {
        return $query->where('type', self::TYPE_CREDIT);
    }

    public function scopeCommissions($query): Builder
    {
        return $query->where('type', self::TYPE_AFFILIATE_COMMISSION);
    }

    public function getTitleAttribute()
    {
        if ($this->type == self::TYPE_DEBIT) {
            return __('Debit');
        } elseif ($this->type == self::TYPE_CREDIT) {
            return __('Credit');
        } elseif ($this->type == self::TYPE_AFFILIATE_COMMISSION) {
            return __('Affiliate commission');
        } elseif ($this->type == self::TYPE_TIP) {
            return __('Tip');
        }
    }
}
