<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   BonusSubscriber.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Listeners;

use App\Models\Bonus;
use App\Repositories\BonusRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;

class BonusSubscriber
{
    protected function giveBonus($event, int $type, float $amount)
    {
        $user = $event->user;
        BonusRepository::create($user->account, $type, $amount);
    }

    public function giveSignUpBonus(Registered $event)
    {
        $this->giveBonus($event, Bonus::TYPE_SIGN_UP, config('settings.bonuses.sign_up'));
    }

    public function giveEmailVerificationBonus(Verified $event)
    {
        $this->giveBonus($event, Bonus::TYPE_EMAIL_VERIFICATION, config('settings.bonuses.email_verification'));
    }

    
    public function subscribe($events)
    {
        
        if (config('settings.bonuses.sign_up') > 0) {
            $events->listen(Registered::class, [self::class, 'giveSignUpBonus']);
        }

        
        if (config('settings.bonuses.email_verification') > 0) {
            $events->listen(Verified::class, [self::class, 'giveEmailVerificationBonus']);
        }
    }
}
