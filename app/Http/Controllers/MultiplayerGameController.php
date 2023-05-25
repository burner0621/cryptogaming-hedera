<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MultiplayerGameController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers;

use App\Helpers\PackageManager;
use App\Http\Requests\GetMultiplayerGame;

class MultiplayerGameController extends Controller
{
    public function index(GetMultiplayerGame $request, $packageId, PackageManager $packageManager)
    {
        $gameServiceClass = $packageManager->getPackageProperty($packageId, 'namespace') . 'Services\\GameService';
        $gameService = (new $gameServiceClass($request->user()))->init();

        return $gameService->getGameActionData('init');
    }
}
