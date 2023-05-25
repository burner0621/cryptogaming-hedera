<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   TestController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Admin;

use App\Helpers\Games\CardSet;
use App\Helpers\Games\PokerHand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function poker(Request $request)
    {
        $communityCards = new CardSet($request->community_cards);

        return collect($request->players)
            ->transform(function ($player) use ($communityCards) {
                return (new PokerHand(new CardSet($player['cards']), $communityCards));
            })
            ->map(function (PokerHand $hand) {
                return [
                    'hand'          => $hand->get()->toArray(),
                    'kickers'       => $hand->getKickers()->toArray(),
                    'combination'   => $hand->getCombination()
                ];
            });
    }
}
