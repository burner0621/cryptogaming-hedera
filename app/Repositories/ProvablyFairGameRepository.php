<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   ProvablyFairGameRepository.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/


namespace App\Repositories;

use App\Models\ProvablyFairGame;
use Illuminate\Support\Carbon;


class ProvablyFairGameRepository
{
    public static function search($hash, $gameableType): ?ProvablyFairGame
    {
        return ProvablyFairGame::where('hash', $hash)
            ->where('gameable_type', $gameableType)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->first();
    }
}
