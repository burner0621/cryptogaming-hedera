<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PlayMultiroomMultiplayerGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Rules\BalanceIsSufficient;
use Illuminate\Foundation\Http\FormRequest;

class PlayMultiroomMultiplayerGame extends FormRequest
{
    protected $gamePackageId;
    protected $gameableClass;

    
    public function authorize()
    {
        $player = $this->room->player($this->user());

        return $this->room->is_open 
            && $player 
            && !$player->game; 
    }

    
    public function rules()
    {
        return [];
    }

    public function withValidator($validator)
    {
        $user = $this->user();
        $bet = $this->room->parameters->bet;

        
        $validator->after(function ($validator) use ($user, $bet) {
            $rule = new BalanceIsSufficient($user, $bet);

            if (!$rule->passes(NULL, NULL)) {
                $validator->errors()->add('balance', $rule->message());
            }
        });
    }
}
