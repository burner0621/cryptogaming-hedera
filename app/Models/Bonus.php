<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Bonus.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use App\Models\Scopes\PeriodScope;
use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;
    use PeriodScope;

    const TYPE_SIGN_UP = 1; 
    const TYPE_DEPOSIT = 2; 
    const TYPE_EMAIL_VERIFICATION = 3; 
    const TYPE_FAUCET = 4;
    const TYPE_RAIN = 5;

    
    protected $appends = [
        'title',
        'created_ago'
    ];

    
    protected $casts = [
        'amount'            => 'float',
        'amount_min'        => 'float',
        'amount_max'        => 'float',
        'amount_avg'        => 'float',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction()
    {
        return $this->morphOne(AccountTransaction::class, 'transactionable');
    }

    public function getTitleAttribute()
    {
        switch ($this->type) {
            case self::TYPE_SIGN_UP:
                return __('Sign up bonus');
                break;

            case self::TYPE_DEPOSIT:
                return __('Deposit bonus');
                break;

            case self::TYPE_EMAIL_VERIFICATION:
                return __('Email verification bonus');
                break;

            case self::TYPE_FAUCET:
                return __('Faucet');
                break;

            case self::TYPE_RAIN:
                return __('Rain');
                break;

            default:
                return __('Bonus');
        }
    }
}
