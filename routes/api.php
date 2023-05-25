<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   api.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

use App\Http\Controllers\Admin\BonusController as AdminBonusController;
use App\Http\Controllers\Admin\HelpController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\Auth\Web3AuthController;
use App\Http\Controllers\MultiplayerGameController;
use App\Http\Controllers\User\FaucetController;
use App\Http\Controllers\User\TipController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController as AdminAccountController;
use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\AffiliateController as AdminAffiliateController;
use App\Http\Controllers\Admin\ChatMessageController;
use App\Http\Controllers\Admin\ChatRoomController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OauthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GameRoomController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\AffiliateController;
use App\Http\Controllers\User\GameController;
use App\Http\Controllers\User\PasswordController;
use App\Http\Controllers\User\TwoFactorAuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Broadcast;




Route::prefix('pages')
    ->group(function () {
        Route::get('{page}', [PageController::class, 'show']);
    });

Route::prefix('pub')
    ->group(function () {
        Route::get('games/recent', [PageController::class, 'recentGames']);
    });


Route::prefix('auth')
    ->middleware(['guest', 'maintenance'])
    ->group(function () {
        Route::post('login', [LoginController::class, 'login']);
        Route::post('register', [RegisterController::class, 'register']);
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
        Route::post('password/reset', [ResetPasswordController::class, 'reset']);

        Route::prefix('web3')
            ->group(function () {
                Route::get('nonce', [Web3AuthController::class, 'nonce'])->name('auth.web3.nonce');
                Route::post('{provider}', [Web3AuthController::class, 'login'])->name('auth.web3.login');
            });
    });


Route::prefix('email')
    ->middleware(['auth:sanctum', 'maintenance', 'active'])
    ->group(function () {
        Route::post('verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
        Route::post('resend', [VerificationController::class, 'resend']);
    });


Route::prefix('auth')
    ->middleware(['auth:sanctum', 'maintenance', 'active'])
    ->group(function () {
        Route::post('logout', [LoginController::class, 'logout']);
    });


Route::prefix('oauth')
    ->middleware(['guest', 'maintenance', 'social'])
    ->group(function () {
        Route::post('{provider}/url', [OauthController::class, 'url']);
        Route::get('{provider}/callback', [OauthController::class, 'callback'])->middleware('log.request');
    });


Route::prefix('user')
    ->middleware(['cookies', 'auth:sanctum', 'maintenance', 'verified', 'active', '2fa', 'last_seen'])
    ->group(function () {
        
        Route::get('', [UserController::class, 'show'])->name('user');
        Route::post('update', [UserController::class, 'update']);
        
        Route::patch('security/password/update', [PasswordController::class, 'update']);
        
        Route::post('security/2fa/enable', [TwoFactorAuthController::class, 'enable']);
        Route::post('security/2fa/confirm', [TwoFactorAuthController::class, 'confirm']);
        Route::post('security/2fa/verify', [TwoFactorAuthController::class, 'verify'])->name('user.security.2fa.verify');
        Route::post('security/2fa/disable', [TwoFactorAuthController::class, 'disable']);
        
        Route::get('affiliate/stats', [AffiliateController::class, 'stats']);
        Route::get('affiliate/commissions', [AffiliateController::class, 'commissions']);
        
        Route::get('account/transactions', [AccountController::class, 'transactions']);
        Route::get('account/faucet', [FaucetController::class, 'show']);
        Route::post('account/faucet', [FaucetController::class, 'update']);
        
        Route::post('games/create', [GameController::class, 'create'])->name('user.games.create');
    });


Broadcast::routes(['middleware' => ['auth:sanctum', 'maintenance', 'verified', 'active', '2fa', 'last_seen']]);


Route::middleware(['cookies', 'auth:sanctum', 'maintenance', 'verified', 'active', '2fa', 'last_seen'])
    ->group(function () {
        
        Route::get('users/{user}', [UserController::class, 'profile']);
        
        Route::post('users/{user}/tip', [TipController::class, 'send']);
        
        Route::get('history/user', [HistoryController::class, 'user']);
        Route::get('history/recent', [HistoryController::class, 'recent']);
        Route::get('history/wins', [HistoryController::class, 'wins']);
        Route::get('history/losses', [HistoryController::class, 'losses']);
        Route::get('history/games/{game}', [HistoryController::class, 'show']);
        Route::get('history/games/{game}/package', [HistoryController::class, 'package']);
        Route::get('history/games/{game}/verify', [HistoryController::class, 'verify']);
        
        Route::get('leaderboard', [LeaderboardController::class, 'index'])->middleware('page.enabled');
        
        Route::get('chat/rooms', [ChatController::class, 'getRooms']);
        Route::get('chat/{room}', [ChatController::class, 'getMessages']);
        Route::post('chat/{room}', [ChatController::class, 'sendMessage']);
        
        Route::get('multiplayer-games/{packageId}', [MultiplayerGameController::class, 'index']);
        
        Route::get('games/{packageId}/rooms', [GameRoomController::class, 'index']);
        Route::post('games/{packageId}/rooms', [GameRoomController::class, 'create']);
        Route::post('games/{packageId}/rooms/join', [GameRoomController::class, 'join'])->middleware('room.lock');
        Route::post('games/{packageId}/rooms/leave', [GameRoomController::class, 'leave'])->middleware('room.lock');
        
        Route::get('assets/{asset}/price', [AssetController::class, 'price'])->name('assets.price');
        Route::get('assets/{asset}/history', [AssetController::class, 'history'])->name('assets.history');
        Route::post('assets/search', [AssetController::class, 'search'])->name('assets.search');
    });


Route::prefix('admin')
    ->middleware(['cookies', 'auth:sanctum', 'verified', 'active', '2fa', 'role:' . App\Models\User::ROLE_ADMIN, 'permissions', 'last_seen'])
    ->group(function () {
        
        Route::get('dashboard/data/{key}', [DashboardController::class, 'getData']);
        
        Route::get('users', [AdminUserController::class, 'index']);
        Route::get('users/{user}', [AdminUserController::class, 'show']);
        Route::patch('users/{user}', [AdminUserController::class, 'update']);
        Route::delete('users/{user}', [AdminUserController::class, 'destroy']);
        Route::post('users/{user}/mail', [AdminUserController::class, 'mail']);
        Route::post('users/search', [AdminUserController::class, 'search'])->name('admin.users.search');
        
        Route::get('accounts', [AdminAccountController::class, 'index']);
        Route::get('accounts/{account}', [AdminAccountController::class, 'show']);
        Route::post('accounts/{account}/debit', [AdminAccountController::class, 'debit']);
        Route::post('accounts/{account}/credit', [AdminAccountController::class, 'credit']);
        Route::get('accounts/{account}/transactions', [AdminAccountController::class, 'transactions']);
        
        Route::get('bonuses', [AdminBonusController::class, 'index']);
        
        Route::get('games', [AdminGameController::class, 'index']);
        
        Route::get('affiliate/tree', [AdminAffiliateController::class, 'tree']);
        Route::get('affiliate/commissions', [AdminAffiliateController::class, 'commissions']);
        Route::get('affiliate/commissions/{commission}', [AdminAffiliateController::class, 'show']);
        Route::post('affiliate/commissions/{commission}/approve', [AdminAffiliateController::class, 'approve']);
        Route::post('affiliate/commissions/{commission}/reject', [AdminAffiliateController::class, 'reject']);
        
        Route::resource('chat/rooms', ChatRoomController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::resource('chat/messages', ChatMessageController::class)->only(['index', 'destroy']);
        
        Route::get('settings', [SettingController::class, 'get']);
        Route::post('settings', [SettingController::class, 'update']);
        Route::post('seeders/asset', [SettingController::class, 'runAssetSeeder']);
        
        Route::post('files', [FileController::class, 'store']);
        Route::patch('files/{name}', [FileController::class, 'update']);
        
        Route::get('maintenance', [MaintenanceController::class, 'index']);
        Route::post('maintenance/up', [MaintenanceController::class, 'up']);
        Route::post('maintenance/down', [MaintenanceController::class, 'down']);
        Route::post('maintenance/command', [MaintenanceController::class, 'command']);
        Route::post('maintenance/migrate', [MaintenanceController::class, 'migrate']);
        Route::post('maintenance/cache', [MaintenanceController::class, 'cache']);
        Route::get('maintenance/jobs', [MaintenanceController::class, 'jobs']);
        Route::get('maintenance/failed-jobs', [MaintenanceController::class, 'failedJobs']);
        Route::post('maintenance/queues/clear', [MaintenanceController::class, 'clearQueue']);
        Route::post('maintenance/queues/stop', [MaintenanceController::class, 'stopQueueWorker']);
        Route::get('maintenance/logs/{file}', [MaintenanceController::class, 'getLogFile'])->name('logs.show');
        Route::delete('maintenance/logs/{file}', [MaintenanceController::class, 'deleteLogFile'])->name('logs.delete');
        Route::get('maintenance/logs/{file}/download', [MaintenanceController::class, 'downloadLogFile'])->name('logs.download');
        
        Route::get('add-ons', [AddonController::class, 'index']);
        Route::get('add-ons/{packageId}', [AddonController::class, 'get']);
        Route::get('add-ons/{packageId}/changelog', [AddonController::class, 'changelog']);
        Route::post('add-ons/{packageId}/enable', [AddonController::class, 'enable']);
        Route::post('add-ons/{packageId}/disable', [AddonController::class, 'disable']);
        Route::post('add-ons/{packageId}/install', [AddonController::class, 'install']);
        Route::post('add-ons/register-bundle', [AddonController::class, 'registerBundle']);
        
        Route::get('license', [LicenseController::class, 'index']);
        Route::post('license', [LicenseController::class, 'register']);
        
        Route::get('help/{file}', [HelpController::class, 'index']);
        
        Route::post('tests/poker', [TestController::class, 'poker']);
    });
