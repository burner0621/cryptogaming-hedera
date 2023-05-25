<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   config.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

return [
    'version'               => '1.2.0',
    'categories'            => json_decode(env('GAME_BACCARAT_CATEGORIES', json_encode(['Cards']))),
    'banner'                => env('GAME_BACCARAT_BANNER', '/images/games/baccarat.jpg'),
    'min_bet'               => env('GAME_BACCARAT_MIN_BET', 1),
    'max_bet'               => env('GAME_BACCARAT_MAX_BET', 50),
    'bet_change_amount'     => env('GAME_BACCARAT_BET_CHANGE_AMOUNT', 1),
    'default_bet_amount'    => env('GAME_BACCARAT_DEFAULT_BET_AMOUNT', 1), 
    'payouts' => [
        'player'    => env('GAME_BACCARAT_PAYOUT_PLAYER', 2),       
        'tie'       => env('GAME_BACCARAT_PAYOUT_TIE', 9),          
        'banker'    => env('GAME_BACCARAT_PAYOUT_BANKER', 1.95),    
    ],
    'sounds'                => [
        'win' => env('GAME_BACCARAT_SOUNDS_WIN'),
        'lose' => env('GAME_BACCARAT_SOUNDS_LOSE'),
        'push' => env('GAME_BACCARAT_SOUNDS_PUSH'),
    ],
];
