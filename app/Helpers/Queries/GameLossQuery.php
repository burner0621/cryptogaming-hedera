<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameLossQuery.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Helpers\Queries;

use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;

class GameLossQuery extends Query
{
    protected $model = Game::class;

    protected $whereClauses = [['win', '=', 0]];

    
    
    protected $with = ['account:id,user_id', 'account.user:id,name,email,avatar,last_seen_at'];

    public function getOrderBy(): string
    {
        return 'bet';
    }

    public function getOrderDirection(): string
    {
        return 'desc';
    }

    public function getRowsToSkip(): int
    {
        return 0;
    }

    public function getItemsPerPage(): int
    {
        return 10;
    }

    public function calculateRowsCount(): int
    {
        return 10;
    }

    protected function getBaseBuilder(): Builder
    {
        return parent::getBaseBuilder()->completed();
    }
}
