<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   DeleteBots.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteBots extends Command
{
    
    protected $signature = 'user:delete {count=10}';

    
    protected $description = 'Delete bots';

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        User::bots()
            ->orderBy('id', 'desc')
            ->limit(intval($this->argument('count')))
            ->get()
            
            
            ->each(function ($user) {
                $user->delete();
            });

        return 0;
    }
}
