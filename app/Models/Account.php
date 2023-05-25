<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Account.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;

    
    protected $casts = [
        'balance' => 'float'
    ];

    protected $appends = ['created_ago', 'updated_ago'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class)->completed();
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class);
    }

    public function commissions()
    {
        return $this->hasMany(AffiliateCommission::class);
    }
}
