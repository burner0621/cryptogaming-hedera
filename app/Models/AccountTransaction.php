<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AccountTransaction.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use App\Models\Scopes\PeriodScope;
use Illuminate\Database\Eloquent\Model;

class AccountTransaction extends Model
{
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;
    use PeriodScope;

    
    protected $hidden = [
        'transactionable_type'
    ];

    
    protected $casts = [
        'amount' => 'float',
        'balance' => 'float',
    ];

    protected $appends = ['created_ago'];

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
