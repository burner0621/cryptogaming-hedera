<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   Web3AuthController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Web3Auth;
use App\Models\SocialProfile;
use App\Repositories\UserRepository;
use Faker\Factory as Faker;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class Web3AuthController extends Controller
{
    public function nonce()
    {
        $nonce = Str::random(20);
        Session::put('nonce', $nonce);
        return ['nonce' => $nonce];
    }

    public function login(Web3Auth $request, string $provider)
    {
        $blockchain = config('auth.web3.' . $provider . '.blockchain');
        $signerClass = sprintf('App\\Services\\Crypto\\%sSigner', Str::ucfirst($blockchain));
        abort_unless(class_exists($signerClass), 404);
        $signer = new $signerClass;
        $nonce = Session::pull('nonce');

        if ($signer->verify($nonce, $request->signature, $request->address)) {
            $user = SocialProfile::where('provider_name', $provider)->where('provider_user_id', $request->address)->first()->user ?? NULL;

            if ($user && !$user->is_active) {
                return $this->errorResponse(__('Your account is blocked.'));
            } elseif (!$user) {
                $faker = Faker::create();

                $user = UserRepository::create([
                    'name'              => $faker->name,
                    'email'             => $faker->email,
                    'email_verified_at' => Carbon::now()
                ]);

                $user->profiles()->create(['provider_name' => $provider, 'provider_user_id' => $request->address]);

                event(new Registered($user));
            }

            
            auth()->guard()->login($user, TRUE);

            return $this->successResponse(['user' => $user]);
        }

        return $this->errorResponse(__('There was an error.'));
    }
}
