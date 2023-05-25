<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   AdminNotification.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        
        $this->locale(config('app.default_locale'));
    }

    
    public function via($notifiable)
    {
        return ['mail'];
    }

    
    public function toArray($notifiable)
    {
        return [
            
        ];
    }
}
