<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Kernel.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console;

use App\Console\Commands\ApproveAffiliateCommissions;
use App\Console\Commands\CreateGames;
use App\Console\Commands\DeleteEmptyGameRooms;
use App\Console\Commands\DeleteUnusedProvablyFairGames;
use App\Console\Commands\MakeRain;
use Database\Seeders\AssetSeeder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Console\Seeds\SeedCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    
    protected $commands = [
        
    ];

    protected $scheduledCommands = [
        DeleteEmptyGameRooms::class => 'daily',
        DeleteUnusedProvablyFairGames::class => 'daily'
    ];

    
    protected function schedule(Schedule $schedule)
    {
        
        $botsFrequencyMethod = config('settings.bots.games.frequency');
        if ($botsFrequencyMethod) {
            $schedule->command(CreateGames::class)->$botsFrequencyMethod();
        }

        
        $rainFrequencyMethod = config('settings.bonuses.rain.frequency');
        if ($rainFrequencyMethod) {
            $schedule->command(MakeRain::class)->$rainFrequencyMethod();
        }

        
        if (config('settings.affiliate.auto_approval_frequency') == 'daily') {
            $schedule->command(ApproveAffiliateCommissions::class)->dailyAt('00:00');
        } elseif (config('settings.affiliate.auto_approval_frequency') == 'weekly') {
            $schedule->command(ApproveAffiliateCommissions::class)->weeklyOn(1, '00:00');
        } elseif (config('settings.affiliate.auto_approval_frequency') == 'monthly') {
            $schedule->command(ApproveAffiliateCommissions::class)->monthlyOn(1, '00:00');
        }

        
        $schedule->command(SeedCommand::class, ['--class' => AssetSeeder::class, '--force'])->daily();

        collect($this->scheduledCommands)->each(function ($frequency, $command) use ($schedule) {
            $schedule->command($command)->$frequency();
        });
    }

    
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
