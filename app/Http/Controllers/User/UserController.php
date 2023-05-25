<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   UserController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\User;

use App\Http\Requests\GetUser;
use App\Http\Requests\UpdateUser;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return $request
            ->user()
            ->append('two_factor_auth_enabled', 'two_factor_auth_passed', 'is_admin')
            ->loadMissing('account', 'profiles');
    }

    
    public function update(UpdateUser $request)
    {
        $user = $request->user();
        $variables = collect($request->only('name', 'hide_profit', 'fields'));

        
        if (!$user->hasVerifiedEmail()) {
            $variables->put('email', $request->email);
        }

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $fileName = $user->id . '_' . time() . '.' . $request->avatar->extension();
            
            $request->avatar->storeAs('avatars', $fileName, 'public');
            $variables->put('avatar', $fileName);
        }

        return tap($user)->update($variables->toArray())->loadMissing('account');
    }

    
    public function profile(GetUser $request, User $user)
    {
        $isAdminOrCurrentUser = $request->user()->is_admin || $request->user()->id == $user->id;

        $getUserStats = function () use ($user, $isAdminOrCurrentUser) {
            return $user
                ->account
                ->games()
                ->selectRaw('COUNT(*) AS bet_count')
                ->selectRaw('IFNULL(SUM(IF(win > bet,1,0)),0) AS win_count')
                ->selectRaw('IFNULL(SUM(bet),0) AS bet_total')
                ->when(!$user->hide_profit || $isAdminOrCurrentUser, function ($query) {
                    $query->selectRaw('IFNULL(SUM(win-bet),0) AS profit_total')
                        ->selectRaw('IFNULL(MAX(win-bet),0) AS profit_max');
                })
                ->get()
                ->map
                ->makeHidden(['title', 'profit', 'is_completed', 'created'])
                ->first();
        };

        $stats = $isAdminOrCurrentUser
            ? $getUserStats()
            : Cache::remember('user.' . $user->id . '.profile', 15*60, $getUserStats);

        return response()->json([
            'user' => $user->only('id', 'name', 'avatar_url', 'created_ago'),
            'stats' => $stats
        ]);
    }
}
