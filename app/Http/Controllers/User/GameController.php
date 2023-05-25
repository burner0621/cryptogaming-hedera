<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\User;

use App\Helpers\PackageManager;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProvablyFairGame;

class GameController extends Controller
{
    
    public function create(CreateProvablyFairGame $request, PackageManager $packageManager)
    {
        $package = $packageManager->get($request->game_package_id);
        $gameServiceClass = $packageManager->getPackageProperty($request->game_package_id, 'namespace') . 'Services\\GameService';

        return $package && $packageManager->enabled($request->game_package_id) && class_exists($gameServiceClass)
            ? (new $gameServiceClass($request->user()))->createProvablyFairGame($request->client_seed)->getProvablyFairGame()
            : ['message' => __('This game does not exist.')];
    }
}
