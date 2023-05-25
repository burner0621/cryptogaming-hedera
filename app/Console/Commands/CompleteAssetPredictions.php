<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CompleteAssetPredictions.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CompleteAssetPredictions extends Command
{
    
    protected $signature = 'prediction:complete {--model=} {--service=}';

    
    protected $description = 'Complete pending asset prediction games.';

    
    public function handle()
    {
        $model = $this->option('model');
        $serviceClass = $this->option('service');

        if ($model && $serviceClass) {
            Game::select('games.*')
                ->inProgress()
                ->where('gameable_type', $model)
                ->join('games_asset_prediction', function ($query) {
                    $query->on('games_asset_prediction.id', '=', 'games.gameable_id');
                    $query->where('end_time', '<=', Carbon::now()->subSeconds(15));
                })
                ->with('account.user')
                ->get()
                ->each(function ($game) use ($serviceClass) {
                    $instance = app()->makeWith($serviceClass, ['user' => $game->account->user]);
                    $instance->complete($game);
                });
        }

        return 0;
    }
}
