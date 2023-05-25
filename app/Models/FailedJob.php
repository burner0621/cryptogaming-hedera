<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   FailedJob.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    use HasFactory;

    protected $casts = [
        'payload' => 'collection'
    ];

    protected $dates = [
        'failed_at'
    ];

    protected $appends = ['failed_ago'];

    
    public function getFailedAgoAttribute()
    {
        return $this->failed_at ? $this->failed_at->diffForHumans() : NULL;
    }
}
