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

namespace App\Http\Controllers\Admin;

use App\Helpers\Queries\UserQuery;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUser;
use App\Models\User;
use App\Notifications\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(UserQuery $query)
    {
        $items = $query
            ->get()
            ->map
            ->makeVisible('referrer')
            ->map
            ->append('two_factor_auth_enabled', 'two_factor_auth_passed', 'is_admin', 'is_bot', 'is_active');

        return [
            'count' => $query->getRowsCount(),
            'items' => $items
        ];
    }

    public function show(User $user)
    {
        return [
            'user' => $user
                ->append('two_factor_auth_enabled')
                ->makeVisible('referrer', 'fields', 'notes')
                ->loadMissing('referrer', 'profiles'),
            'roles' => User::roles(),
            'permissions' => User::permissions(),
            'access_modes' => User::accessModes(),
        ];
    }

    public function update(UpdateUser $request, User $user)
    {
        foreach ($request->all() as $property => $value) {
            if ($property == 'password' && $value) {
                $user->password = Hash::make($value);
            } elseif ($property == 'avatar') {
                $user->avatar = Str::afterLast($request->avatar, '/');
            } elseif (Schema::hasColumn($user->getTable(), $property)) {
                $user->{$property} = $value;
            }
        }

        return $user->save();
    }

    
    public function destroy(Request $request, User $user)
    {
        
        if ($request->user()->id == $user->id) {
            abort(409, __('You can not delete currently logged user.'));
        }

        
        return $user->delete();
    }

    
    public function mail(Request $request, User $user)
    {
        try {
            $user->notify(new UserMessage($request->subject, $request->greeting, $request->message));
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            abort(500, $e->getMessage());
        }

        return TRUE;
    }

    public function search(Request $request)
    {
        return User::select('id', 'name', 'email')
            ->when(is_numeric($request->search), function ($query) use ($request) {
                $query->where('id', $request->search);
            })
            ->when(!is_numeric($request->search), function ($query) use ($request) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($request->search) . '%']);
            })
            ->orderBy('name')
            ->get();
    }
}
