<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   CallArtisanCommand.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CallArtisanCommand extends Command
{
    
    protected $signature = 'command:call {name=cache:clear}';

    
    protected $description = 'Call artisan command';

    
    public function handle()
    {
        Artisan::call($this->argument('name'));

        return 0;
    }
}
