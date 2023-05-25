<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   UserRepository.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Repositories;

use App\Helpers\Api\UserAvatarApi;
use App\Helpers\Utils;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserRepository
{
    
    public static function create($properties)
    {
        Log::info(sprintf('New user: %s', json_encode($properties)));

        $avatar = $properties['avatar'] ?? NULL;

        if (Str::of($avatar)->contains(['http://', 'https://'])) {
            try {
                $client = new Client();
                $response = $client->get($avatar);
                if ($response->getStatusCode() == 200) {
                    $fileName = sprintf('%s_%s.jpg', time(), Utils::generateRandomString(6));
                    
                    if (Storage::disk('public')->put('avatars/' . $fileName, $response->getBody()->getContents())) {
                        $avatar = $fileName;
                    }
                } else {
                    Log::error(sprintf('Can not retrieve remote avatar %s', $avatar));
                }
            } catch (\Throwable $e) {
                Log::error(sprintf('OAuth avatar capture: %s', $e->getMessage()));
            }
        } elseif (!$avatar && config('settings.users.create_random_avatar')) {
            $api = app()->make(UserAvatarApi::class);
            if ($file = $api->downloadAvatar()) {
                $fileName = sprintf('%s_%s.svg', time(), Utils::generateRandomString(6));
                if (Storage::disk('public')->put('avatars/' . $fileName, $file)) {
                    $avatar = $fileName;
                }
            }
        }

        $referrerId = Cookie::has('ref') ? Cookie::get('ref') : NULL;

        
        if ($referrerId) {
            
            $referrer = config('settings.affiliate.hash_user_id')
                ? User::where(DB::raw('MD5(code)'), $referrerId)->active()->first()
                : User::where('id', $referrerId)->active()->first();

            info(sprintf('Referrer user ID: %d', optional($referrer)->id));

            
            if ($referrer && (config('settings.affiliate.allow_same_ip') || $referrer->last_login_from != request()->ip())) {
                $referrerId = $referrer->id;
            } else {
                $referrerId = NULL;
            }
        }

        $userFields = collect(config('settings.users.fields'));
        $fields = $userFields->isNotEmpty()
            ? $userFields->mapWithKeys(function ($field) use ($properties) {
                return [$field->id => data_get($properties, 'fields.' . $field->id)];
            })
            : NULL;

        $user = new User();
        $user->referrer_id          = $referrerId;
        $user->name                 = $properties['name'];
        $user->email                = $properties['email'];
        $user->code                 = (string) Str::uuid();
        $user->role                 = $properties['role'] ?? User::ROLE_USER;
        $user->avatar               = $avatar;
        $user->password             = Hash::make($properties['password'] ?? Str::random());
        $user->fields               = $fields;
        $user->status               = User::STATUS_ACTIVE;
        $user->email_verified_at    = $properties['email_verified_at'] ?? NULL;
        $user->last_login_at        = $properties['last_login_at'] ?? Carbon::now();
        $user->last_login_from      = $properties['last_login_from'] ?? request()->ip();
        $user->save();

        AccountRepository::create($user);

        return $user;
    }

    
    public static function load(User $user): User
    {
        return $user
            ->makeVisible('fields')
            ->append('two_factor_auth_enabled', 'two_factor_auth_passed', 'is_admin')
            ->loadMissing('account', 'profiles');
    }
}
