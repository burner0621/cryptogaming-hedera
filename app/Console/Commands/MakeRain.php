<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MakeRain.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Console\Commands;

use App\Facades\ChatMessage;
use App\Models\Bonus;
use App\Models\ChatRoom;
use App\Models\User;
use App\Repositories\BonusRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MakeRain extends Command
{
    
    protected $signature = 'rain:make';

    
    protected $description = 'Make credits rain';

    
    public function __construct()
    {
        parent::__construct();
    }

    
    public function handle()
    {
        $frequency = config('settings.bonuses.rain.frequency');
        $amounts = config('settings.bonuses.rain.amounts');
        $chatUserId = config('settings.bonuses.rain.user');

        if (config('settings.interface.chat.enabled') && $frequency && count($amounts) > 0 && $chatUserId) {
            $startTime = Carbon::now();

            if ($frequency == 'everyFifteenMinutes') {
                $startTime->subMinutes(15);
            } elseif ($frequency == 'everyThirtyMinutes') {
                $startTime->subMinutes(30);
            } elseif ($frequency == 'hourly') {
                $startTime->subHour();
            } elseif ($frequency == 'everyTwoHours') {
                $startTime->subHours(2);
            } elseif ($frequency == 'everyThreeHours') {
                $startTime->subHours(3);
            } elseif ($frequency == 'everyFourHours') {
                $startTime->subHours(4);
            } elseif ($frequency == 'everySixHours') {
                $startTime->subHours(6);
            } elseif ($frequency == 'daily') {
                $startTime->subDay();
            } elseif ($frequency == 'weekly') {
                $startTime->subWeek();
            } elseif ($frequency == 'monthly') {
                $startTime->subMonth();
            }

            $users = User::regular()
                ->select('users.id', 'users.name', DB::raw('LENGTH(REPLACE(GROUP_CONCAT(chat_messages.message SEPARATOR ""), " ", "")) AS messages_length'))
                ->join('chat_messages', function ($query) use ($startTime) {
                    $query->on('chat_messages.user_id', '=', 'users.id');
                    $query->where('chat_messages.created_at', '>=', $startTime);
                })
                ->with('account')
                ->groupBy('users.id', 'users.name')
                ->orderBy('messages_length', 'desc')
                ->take(count($amounts))
                ->get();

            info(sprintf('Making rain to users: %s', $users->isNotEmpty() ? $users->pluck('id') : 'none'));

            if ($users->isNotEmpty()) {
                $users->each(function ($user, $i) use ($amounts) {
                    BonusRepository::create($user->account, Bonus::TYPE_RAIN, $amounts[$i]);
                });

                ChatRoom::enabled()->get()->each(function (ChatRoom $room) use ($users, $chatUserId, $amounts) {
                    ChatMessage::create(
                        $room,
                        User::find($chatUserId),
                        __('Credit rain for the following users')
                            . ': '
                            . $users->map(function ($user, $i) use ($amounts) {
                                return $user->name . ' (' . $amounts[$i] . __('credits') . ')';
                            })->implode(', '),
                        $users->pluck('id')->toArray()
                    );
                });
            }
        }

        return 0;
    }
}
