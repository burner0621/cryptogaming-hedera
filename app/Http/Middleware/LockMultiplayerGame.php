<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LockMultiplayerGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Middleware;

use App\Models\MultiplayerGame;
use Closure;
use Illuminate\Support\Facades\DB;

class LockMultiplayerGame
{
    
    public function handle($request, Closure $next)
    {
        
        DB::beginTransaction();

        
        
        $multiplayerGame = $request->route('multiplayerGame');
        if ($multiplayerGame instanceof MultiplayerGame) {
            $multiplayerGame->gameable()->lockForUpdate()->get();
        }

        
        $response = $next($request);

        
        
        DB::commit();

        return $response;
    }
}
