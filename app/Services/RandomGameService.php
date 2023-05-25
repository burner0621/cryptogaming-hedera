<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   RandomGameService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;

use App\Models\User;

interface RandomGameService
{
    
    public static function createRandomGame(User $user): void;
}
