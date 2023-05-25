<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Baccarat.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace Packages\Baccarat\Models;

use App\Models\Gameable;
use App\Models\ProvableGame;
use Illuminate\Database\Eloquent\Model;
use Packages\Baccarat\Services\GameService;

class Baccarat extends Model implements ProvableGame
{
    use Gameable;

    const BET_TYPE_PLAYER   = 0;
    const BET_TYPE_TIE      = 1;
    const BET_TYPE_BANKER   = 2;

    
    protected $table = 'games_baccarat';

    protected $attributes = [
        'player_bet' => 0,
        'player_win' => 0,
        'banker_bet' => 0,
        'banker_win' => 0,
        'tie_bet' => 0,
        'tie_win' => 0,
    ];

    
    protected $casts = [
        'deck' => 'array',
        'player_bet' => 'integer',
        'banker_bet' => 'integer',
        'tie_bet' => 'integer',
        'player_win' => 'float',
        'banker_win' => 'float',
        'tie_win' => 'float',
        'player_hand' => 'array',
        'banker_hand' => 'array',
        'player_score' => 'integer',
        'banker_score' => 'integer',
    ];

    protected $appends = ['player_result', 'banker_result', 'win_type', 'initial_player_hand', 'initial_player_score', 'initial_banker_hand', 'initial_banker_score'];

    
    public function getPlayerResultAttribute(): string
    {
        if ($this->player_score > $this->banker_score) {
            return __('Win');
        } else if ($this->player_score < $this->banker_score) {
            return __('Lose');
        } else {
            return __('Tie');
        }
    }

    
    public function getBankerResultAttribute(): string
    {
        if ($this->player_score < $this->banker_score) {
            return __('Win');
        } else if ($this->player_score > $this->banker_score) {
            return __('Lose');
        } else {
            return __('Tie');
        }
    }

    
    public function getWinTypeAttribute(): int
    {
        if ($this->player_score > $this->banker_score) {
            return self::BET_TYPE_PLAYER;
        } else if ($this->player_score < $this->banker_score) {
            return self::BET_TYPE_BANKER;
        } else {
            return self::BET_TYPE_TIE;
        }
    }

    
    public function getInitialPlayerHandAttribute()
    {
        return is_array($this->player_hand) ? array_slice($this->player_hand, 0, 2) : [];
    }

    
    public function getInitialPlayerScoreAttribute()
    {
        return is_array($this->initial_player_hand) ? GameService::calculateHandScore($this->initial_player_hand) : 0;
    }

    
    public function getInitialBankerHandAttribute()
    {
        return is_array($this->banker_hand) ? array_slice($this->banker_hand, 0, 2) : [];
    }

    
    public function getInitialBankerScoreAttribute()
    {
        return is_array($this->initial_banker_hand) ? GameService::calculateHandScore($this->initial_banker_hand) : 0;
    }

    
    public function getSecretAttributeAttribute(): string
    {
        return 'deck';
    }

    
    public function getSecretAttributeHintAttribute(): string
    {
        return __('The first N cards are removed from the top of the deck and placed under the remaining cards. N is the remainder of dividing the Shift value by 52.');
    }
}
