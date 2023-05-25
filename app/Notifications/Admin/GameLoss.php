<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   GameLoss.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Notifications\Admin;

use App\Models\Game;
use Illuminate\Notifications\Messages\MailMessage;

class GameLoss extends AdminNotification
{
    protected $game;

    
    public function __construct(Game $game)
    {
        parent::__construct();

        $this->game = $game;
    }

    
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('User lost a game'))
            ->greeting(__('Hello'))
            ->line(__('User :name (:email) lost :n credits in :game (game :id).', [
                'name'  => $this->game->account->user->name,
                'email' => $this->game->account->user->email,
                'n'     => abs($this->game->profit),
                'game'  => $this->game->title,
                'id'    => $this->game->id,
            ]))
            ->action(__('View game details'), url(sprintf('admin/games/%d', $this->game->id)));
    }
}
