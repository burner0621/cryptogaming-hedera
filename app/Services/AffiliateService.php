<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AffiliateService.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services;

use App\Models\Account;
use App\Models\AffiliateCommission;
use App\Models\PercentageAffiliateCommission;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AffiliateService
{
    private $account; 

    
    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    
    public function createCommissions(Model $commissionable, int $type): void
    {
        
        $tierUser = $this->account->user;

        
        for ($tier=0; $tier<=2; $tier++) {
            
            if ($tierUser->referrer) {
                
                $this->createCommission(
                    $commissionable, 
                    $tierUser->referrer, 
                    $tier + 1,
                    $type,
                    $this->calculateCommissionAmount($commissionable, $type, $tier) 
                );

                $tierUser = $tierUser->referrer;
            } else {
                break;
            }
        }
    }

    
    private function createCommission(Model $commissionable, User $referrer, int $tier, int $type, float $amount): ?AffiliateCommission
    {
        if ($amount == 0) {
            return NULL;
        }

        
        if (!config('settings.affiliate.allow_same_ip') && $referrer->last_login_from == request()->ip()) {
            Log::debug(sprintf('Ignore affiliate commission for referrer user ID %d due to the same IP address.', $referrer->id));
            return NULL;
        }

        
        $commission = new AffiliateCommission();
        $commission->account()->associate($referrer->account);
        $commission->referralAccount()->associate($this->account);
        $commission->tier = $tier;
        $commission->type = $type;
        $commission->status = AffiliateCommission::STATUS_PENDING;
        $commission->amount = $amount;
        $commission->ip = request()->ip();
        $commissionable->commission()->save($commission);

        return $commission;
    }

    
    private function calculateCommissionAmount(Model $commissionable, int $type, int $tier): float
    {
        $key = NULL;

        if ($type == AffiliateCommission::TYPE_SIGN_UP) {
            $key = 'sign_up';
        } elseif ($type == AffiliateCommission::TYPE_GAME_LOSS) {
            $key = 'game_loss';
        } elseif ($type == AffiliateCommission::TYPE_GAME_WIN) {
            $key = 'game_win';
        } elseif ($type == AffiliateCommission::TYPE_DEPOSIT) {
            $key = 'deposit';
        } elseif ($type == AffiliateCommission::TYPE_RAFFLE_TICKET) {
            $key = 'raffle_ticket';
        }

        if (!$key) {
            return 0;
        }

        $commission = config('settings.affiliate.commissions.' . $key);
        $rates = $commission['rates'];

        return $commission['type'] == 'percentage' && $commissionable instanceof PercentageAffiliateCommission
            ? $commissionable->getAffiliateCommissionBaseAmount() * $rates[$tier] / 100
            : $rates[$tier];
    }
}
