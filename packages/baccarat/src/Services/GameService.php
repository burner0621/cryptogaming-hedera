<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace Packages\Baccarat\Services;

use App\Helpers\Games\CardDeck;
use App\Models\Game;
use App\Models\User;
use App\Services\GameService as ParentGameService;
use App\Services\RandomGameService;
use Packages\Baccarat\Models\Baccarat;

class GameService extends ParentGameService implements RandomGameService
{
    protected $gameableClass = Baccarat::class;

    protected $deck;

    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->deck = new CardDeck();
    }

    public function makeSecret(): string
    {
        return implode(',', $this->deck->toArray());
    }

    
    public function play($params): GameService
    {
        $provablyFairGame = $this->getProvablyFairGame();

        
        $this->deck->set(explode(',', $provablyFairGame->secret));
        
        $this->deck->cut($provablyFairGame->shift_value % 52);

        $gameable = new $this->gameableClass();
        $gameable->deck = $this->deck->toArray();
        $gameable->player_bet = $params['player_bet'] ?: 0;
        $gameable->banker_bet = $params['banker_bet'] ?: 0;
        $gameable->tie_bet = $params['tie_bet'] ?: 0;
        $gameable->player_hand = [$this->deck->getCard(1)->code, $this->deck->getCard(3)->code];
        $gameable->banker_hand = [$this->deck->getCard(2)->code, $this->deck->getCard(4)->code];









        $gameable->player_score = self::calculateHandScore($gameable->player_hand);
        $gameable->banker_score = self::calculateHandScore($gameable->banker_hand);

        
        $this->gameable = $gameable;

        
        if ($gameable->player_score < 8 && $gameable->banker_score < 8) {
            
            if ($gameable->player_score <= 5) {
                $gameable->player_hand = array_merge($gameable->player_hand, [$this->deck->getCard(5)->code]);
            }

            
            if (($gameable->player_score == 6 || $gameable->player_score == 7) && $gameable->banker_score <= 5) {
                $gameable->banker_hand = array_merge($gameable->banker_hand, [$this->deck->getCard(5)->code]);
            
            } elseif (count($gameable->player_hand) == 3) {
                
                if ($gameable->banker_score <= 2
                    
                    || $gameable->banker_score == 3 && $gameable->player_hand[2][1] != 8
                    
                    || $gameable->banker_score == 4 && in_array((int)$gameable->player_hand[2][1], [2, 3, 4, 5, 6, 7])
                    
                    || $gameable->banker_score == 5 && in_array((int)$gameable->player_hand[2][1], [4, 5, 6, 7])
                    
                    || $gameable->banker_score == 6 && in_array((int)$gameable->player_hand[2][1], [6, 7])) {
                    $gameable->banker_hand = array_merge($gameable->banker_hand, [$this->deck->getCard(6)->code]);
                }
            }
        }

        $gameable->player_score = self::calculateHandScore($gameable->player_hand);
        $gameable->banker_score = self::calculateHandScore($gameable->banker_hand);

        if ($gameable->player_score > $gameable->banker_score && $gameable->player_bet > 0) {
            $gameable->player_win = $gameable->player_bet * (float) config('baccarat.payouts.player');
        }

        if ($gameable->player_score < $gameable->banker_score && $gameable->banker_bet > 0) {
            $gameable->banker_win = $gameable->banker_bet * (float) config('baccarat.payouts.banker');
        }

        if ($gameable->player_score == $gameable->banker_score && $gameable->tie_bet) {
            $gameable->tie_win = $gameable->tie_bet * (float) config('baccarat.payouts.tie');
        }

        $this->save([
            'bet'       => $gameable->player_bet + $gameable->banker_bet + $gameable->tie_bet,
            'win'       => $gameable->player_win + $gameable->banker_win + $gameable->tie_win,
            'status'    => Game::STATUS_COMPLETED
        ]);

        return $this;
    }

    
    public static function calculateHandScore(array $hand): int
    {
        $score = 0;

        $getCardScore = function ($cardValue) {
            if (intval($cardValue) > 0)
                return intval($cardValue);
            
            elseif ($cardValue == 'A')
                return 1;
            
            else
                return 0;
        };

        
        foreach ($hand as $card) {
            $score += $getCardScore(substr($card, 1, 1));
        }

        
        return $score < 10 ? $score : (int) substr($score, 1, 1);
    }

    public static function createRandomGame(User $user): void
    {
        $instance = new self($user);

        $instance->createProvablyFairGame(random_int(10000,99999));

        $minBet = intval(config('settings.bots.games.min_bet'));
        $maxBet = intval(config('settings.bots.games.max_bet'));

        $playerBet = random_int(0, 1);
        $bankerBet = random_int(0, 1);
        $tieBet = random_int(0, 1);

        if (!$playerBet && !$bankerBet && !$tieBet) {
            $playerBet = 1;
        }

        $instance->play([
            'player_bet' => $playerBet ? random_int($minBet ?: config('baccarat.min_bet'), $maxBet ?: config('baccarat.max_bet')) : NULL,
            'banker_bet' => $bankerBet ? random_int($minBet ?: config('baccarat.min_bet'), $maxBet ?: config('baccarat.max_bet')) : NULL,
            'tie_bet' => $tieBet ? random_int($minBet ?: config('baccarat.min_bet'), $maxBet ?: config('baccarat.max_bet')) : NULL,
        ]);
    }
}
