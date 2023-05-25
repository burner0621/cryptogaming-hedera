<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   TwitterExtendSocialite.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Services\Oauth;

use SocialiteProviders\Manager\SocialiteWasCalled;

class TwitterExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('twitter', TwitterProvider::class);
    }
}
