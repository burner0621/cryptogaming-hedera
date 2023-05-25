<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameHistoryQuery.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries;

use App\Helpers\Queries\Filters\GameFilter;
use App\Helpers\Queries\Filters\GameResultFilter;
use App\Helpers\Queries\Filters\PeriodFilter;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;

class GameHistoryQuery extends Query
{
    protected $model = Game::class;

    protected $filters = [[PeriodFilter::class, GameFilter::class, GameResultFilter::class]];

    protected $sortableColumns = [
        'title'         => 'gameable_type',
        'bet'           => 'bet',
        'win'           => 'win',
        'profit'        => '(win - bet)',
        'created_ago'   => 'created_at'
    ];

    protected function getBaseBuilder(): Builder
    {
        return parent::getBaseBuilder()->completed();
    }
}
