<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MultiplayerGameable.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait MultiplayerGameable
{
    use StandardDateFormat;

    
    public function multiplayerGame(): MorphOne
    {
        return $this->morphOne(MultiplayerGame::class, 'gameable');
    }
}
