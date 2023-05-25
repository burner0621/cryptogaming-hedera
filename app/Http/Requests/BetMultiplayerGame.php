<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   BetMultiplayerGame.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Requests;

use App\Helpers\PackageManager;
use App\Models\MultiplayerGame;
use App\Models\User;
use App\Rules\BalanceIsSufficient;
use Illuminate\Foundation\Http\FormRequest;

class BetMultiplayerGame extends FormRequest
{
    protected $gameableClass;

    
    public function authorize()
    {
        return $this->multiplayerGame instanceof MultiplayerGame
            && $this->multiplayerGame->gameable_type == $this->gameableClass
            && $this->multiplayerGame->is_betting_in_progress;
    }

    
    public function rules()
    {
        $packageManager = app()->make(PackageManager::class);
        $packageId = $packageManager->getPackageIdByClass($this->gameableClass);

        return [
            'bet' => [
                'required',
                'integer',
                'min:' . config($packageId . '.min_bet'),
                'max:' . config($packageId . '.max_bet'),
            ]
        ];
    }

    protected function validateBalance($validator, User $user, int $bet)
    {
        $validator->after(function ($validator) use ($user, $bet) {
            $rule = new BalanceIsSufficient($user, $bet);

            if (!$rule->passes()) {
                $validator->errors()->add('balance', $rule->message());
            }
        });
    }
}
