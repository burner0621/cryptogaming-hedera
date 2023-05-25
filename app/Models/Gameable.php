<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Gameable.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Gameable
{
    use StandardDateFormat;

    
    public function game(): MorphOne
    {
        return $this->morphOne(Game::class, 'gameable');
    }

    
    public function games(): MorphMany
    {
        return $this->morphMany(Game::class, 'gameable');
    }

    
    public function getIsProvablyFairAttribute(): bool
    {
        return $this instanceof ProvableGame;
    }
}
