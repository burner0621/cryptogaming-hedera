<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   DeleteEmptyGameRooms.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use App\Models\GameRoom;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteEmptyGameRooms extends Command
{
    
    protected $signature = 'game-room:delete';

    
    protected $description = 'Delete empty game rooms';

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        
        
        GameRoom::where(function ($query) {
                $query->doesntHave('players')->where('updated_at', '<', Carbon::now()->subDay());
            })
            ->orWhere('updated_at', '<', Carbon::now()->subDays(3))
            ->delete();

        return 0;
    }
}
