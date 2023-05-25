<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   PlayGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Rules\BalanceIsSufficient;
use App\Repositories\ProvablyFairGameRepository;
use Illuminate\Foundation\Http\FormRequest;

class PlayGame extends FormRequest
{
    protected $gamePackageId;
    protected $gameableClass;

    
    public function authorize()
    {
        if ($this->hash) {
            $provablyFairGame = ProvablyFairGameRepository::search($this->hash, $this->gameableClass);
            return $provablyFairGame && !$provablyFairGame->game;
        }

        return FALSE;
    }

    
    public function rules()
    {
        return [
            'hash' => 'required|size:64',
            'bet' => [
                'required',
                'integer',
                'min:' . config($this->gamePackageId . '.min_bet'),
                'max:' . config($this->gamePackageId . '.max_bet'),
            ]
        ];
    }

    public function withValidator($validator)
    {
        $user = $this->user();
        $amount = $this->bet;

        
        $validator->after(function ($validator) use ($user, $amount) {
            $rule = new BalanceIsSufficient($user, $amount);

            if (!$rule->passes()) {
                $validator->errors()->add('bet', $rule->message());
            }
        });
    }
}
