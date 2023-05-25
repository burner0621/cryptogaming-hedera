<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   VerifyEmail.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as Notification;

class VerifyEmail extends Notification
{
    
    protected function verificationUrl($notifiable)
    {
        $url = parent::verificationUrl($notifiable);

        return str_replace('/api', '', $url);
    }
}
