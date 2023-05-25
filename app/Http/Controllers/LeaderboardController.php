<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   LeaderboardController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers;

use App\Helpers\Queries\LeaderboardQuery;

class LeaderboardController extends Controller
{
    public function index(LeaderboardQuery $query)
    {
        return [
            'count' => $query->getRowsCount(),
            'items' => $query->get()->makeHidden('email')
        ];
    }
}
