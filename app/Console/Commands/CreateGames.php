<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CreateGames.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use App\Events\UserIsOnline;
use App\Helpers\PackageManager;
use App\Models\MultiplayerGame;
use App\Models\User;
use App\Services\RandomGameService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CreateGames extends Command
{
    
    protected $signature = 'game:create';

    
    protected $description = 'Create games';

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle(PackageManager $packageManager)
    {
        $count = random_int(config('settings.bots.games.min_count'), config('settings.bots.games.max_count'));

        
        $bots = User::active()
            ->bots()
            ->inRandomOrder()
            ->limit($count)
            ->get();

        
        if (!$bots->isEmpty()) {
            
            $gameServiceClasses = [];

            foreach ($packageManager->getEnabled() as $package) {
                if ($package->type == 'game') {
                    $gameServiceClass = $package->namespace . 'Services\\GameService';

                    if (class_exists($gameServiceClass) && is_a($gameServiceClass, RandomGameService::class, TRUE)) {
                        $gameServiceClasses[] = $gameServiceClass;
                    }
                } elseif ($package->type == 'prediction') {
                    $gameServiceClass = $package->namespace . 'Services\\AssetPredictionService';

                    if (class_exists($gameServiceClass) && is_a($gameServiceClass, RandomGameService::class, TRUE)) {
                        $gameServiceClasses[] = $gameServiceClass;
                    }
                }
            }

            
            $n = count($gameServiceClasses);

            if ($n > 0) {
                
                $bots->each(function ($bot) use ($gameServiceClasses, $n) {
                    
                    event(new UserIsOnline($bot));
                    tap($bot, function ($bot) { $bot->is_online = TRUE; })->save();

                    
                    $i = random_int(0, $n - 1);
                    
                    call_user_func_array([$gameServiceClasses[$i], 'createRandomGame'], [$bot]);
                });
            }
        }

        return 0;
    }
}
