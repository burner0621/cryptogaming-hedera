<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   User.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Models;

use App\Casts\UserFields;
use App\Models\Scopes\PeriodScope;
use App\Models\Scopes\UserRoleScope;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use DefaultTimestampsAgoAttributes;
    use StandardDateFormat;
    use PeriodScope;
    use UserRoleScope;

    const ROLE_USER  = 1;
    const ROLE_ADMIN = 2;
    const ROLE_BOT   = 4;

    const STATUS_ACTIVE  = 0;
    const STATUS_BLOCKED = 1;

    
    const PERMISSION_ACCOUNTS = 'accounts';
    const PERMISSION_BONUSES = 'bonuses';
    const PERMISSION_ADDONS = 'add-ons';
    const PERMISSION_AFFILIATE = 'affiliate';
    const PERMISSION_CHAT = 'chat';
    const PERMISSION_DASHBOARD = 'dashboard';
    const PERMISSION_GAMES = 'games';
    const PERMISSION_LICENSE = 'license';
    const PERMISSION_HELP = 'help';
    const PERMISSION_MAINTENANCE = 'maintenance';
    const PERMISSION_SETTINGS = 'settings';
    const PERMISSION_USERS = 'users';
    
    const PERMISSION_DEPOSITS = 'deposits';
    const PERMISSION_DEPOSIT_METHODS = 'deposit-methods';
    const PERMISSION_WITHDRAWALS = 'withdrawals';
    const PERMISSION_WITHDRAWAL_METHODS = 'withdrawal-methods';
    
    const PERMISSION_RAFFLES = 'raffles';

    const ACCESS_NONE = 0;
    const ACCESS_READONLY = 1;
    const ACCESS_FULL = 10;

    
    protected $fillable = [
        'name', 'email', 'code', 'password', 'avatar', 'hide_profit', 'email_verified_at', 'fields'
    ];

    
    protected $hidden = [
        'code', 'password', 'remember_token', 'totp_secret', 'referrer_id', 'referrer', 'fields', 'notes'
    ];

    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'bet_count'         => 'integer',
        'bet_total'         => 'float',
        'profit_total'      => 'float',
        'profit_max'        => 'float',
        'hide_profit'       => 'boolean',
        'banned_from_chat'  => 'boolean',
        'permissions'       => 'collection',
        'role'              => 'integer',
        'status'            => 'integer',
        'fields'            => UserFields::class
    ];

    
    protected $dates = [
        'last_login_at', 'email_verified_at', 'last_seen_at'
    ];

    
    protected $appends = [
        'avatar_url', 'gravatar_url', 'affiliate_url', 'created_ago', 'last_seen_ago', 'role_title', 'status_title'
    ];

    
    public function referrer()
    {
        return $this->belongsTo(User::class);
    }

    
    public function referees()
    {
        return $this->hasMany(User::class, 'referrer_id');
    }

    
    public function scopeActive($query): Builder
    {
        return $query->where('users.status', '=', self::STATUS_ACTIVE);
    }

    
    public function scopeBots($query): Builder
    {
        return $query->where('users.role', '=', self::ROLE_BOT);
    }

    
    public function scopeRegular($query)
    {
        return $query->where('users.role', '=', self::ROLE_USER);
    }

    
    public function scopeNotAdmin($query)
    {
        return $query->where('users.role', '!=', self::ROLE_ADMIN);
    }

    
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function transactions()
    {
        return $this->hasManyThrough(AccountTransaction::class, Account::class);
    }

    public function commission()
    {
        return $this->morphMany(AffiliateCommission::class, 'commissionable');
    }

    public function games()
    {
        return $this->hasManyThrough(Game::class, Account::class);
    }

    
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    
    public function getGravatarUrlAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email)).'.jpg?s=100&d=mp';
    }

    
    public function setHideProfitAttribute($value)
    {
        $this->attributes['hide_profit'] = is_bool($value) ? $value : ($value == 1 || $value == 'true');
    }

    
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : $this->gravatar_url;
    }

    
    public function getAffiliateUrlAttribute(): string
    {
        return config('settings.affiliate.hash_user_id')
        ? url('?ref=' . md5($this->code))
        : url('?ref=' . $this->id);
    }

    
    public function getTwoFactorAuthEnabledAttribute()
    {
        return $this->totp_secret ? TRUE : FALSE;
    }

    
    public function getTwoFactorAuthPassedAttribute()
    {
        return request()->session()->get('two_factor_auth_passed', FALSE);
    }

    
    public function getIsAdminAttribute()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    
    public function getIsBotAttribute(): bool
    {
        return $this->hasRole(self::ROLE_BOT);
    }

    
    public function getIsActiveAttribute(): bool
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    
    public function getIsOnlineAttribute(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gte(Carbon::now()->subSeconds($this->is_bot ? 300 : 120));
    }

    
    public function setIsOnlineAttribute(bool $value)
    {
        if ($value) {
            $this->attributes['last_seen_at'] = Carbon::now();
        }
    }

    
    public function profiles()
    {
        return $this->hasMany(SocialProfile::class);
    }

    
    public function delete()
    {
        if ($this->avatar) {
            Storage::disk('public')->delete('avatars/' . $this->avatar);
        }

        return parent::delete();
    }

    
    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at) || $this->is_bot;
    }

    
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    
    public function hasRole($role)
    {
        return isset($this->role) && $this->role == $role;
    }

    
    public function hasReadOnlyAccess(string $module)
    {
        return is_null($this->permissions) || (int)$this->permissions->get($module) >= self::ACCESS_READONLY;
    }

    
    public function hasFullAccess(string $module)
    {
        return is_null($this->permissions) || (int)$this->permissions->get($module) >= self::ACCESS_FULL;
    }

    
    public function getTotpSecretAttribute($value)
    {
        return $value ? decrypt($value) : NULL;
    }

    
    public function setTotpSecretAttribute($value)
    {
        $this->attributes['totp_secret'] = encrypt($value);
    }

    public static function roles()
    {
        return [
            self::ROLE_USER => __('User'),
            self::ROLE_ADMIN => __('Admin'),
            self::ROLE_BOT => __('Bot'),
        ];
    }

    public static function statuses()
    {
        return [
            self::STATUS_ACTIVE => __('Active'),
            self::STATUS_BLOCKED => __('Blocked'),
        ];
    }

    public static function accessModes()
    {
        return [
            self::ACCESS_NONE => __('None'),
            self::ACCESS_READONLY => __('Read only'),
            self::ACCESS_FULL => __('Full'),
        ];
    }

    public static function permissions()
    {
        return [
            self::PERMISSION_DASHBOARD => __('Dashboard'),
            self::PERMISSION_USERS => __('Users'),
            self::PERMISSION_ACCOUNTS => __('Accounts'),
            self::PERMISSION_BONUSES => __('Bonuses'),
            self::PERMISSION_AFFILIATE => __('Affiliate'),
            self::PERMISSION_GAMES => __('Games'),
            self::PERMISSION_CHAT => __('Chat'),
            self::PERMISSION_SETTINGS => __('Settings'),
            self::PERMISSION_MAINTENANCE => __('Maintenance'),
            self::PERMISSION_ADDONS => __('Add-ons'),
            self::PERMISSION_LICENSE => __('License'),
            self::PERMISSION_HELP => __('Help'),
            self::PERMISSION_RAFFLES => __('Raffles'),
            self::PERMISSION_DEPOSITS => __('Deposits'),
            self::PERMISSION_DEPOSIT_METHODS => __('Deposit methods'),
            self::PERMISSION_WITHDRAWALS => __('Withdrawals'),
            self::PERMISSION_WITHDRAWAL_METHODS => __('Withdrawal methods'),
        ];
    }

    
    public function getLastSeenAgoAttribute()
    {
        return $this->last_seen_at ? $this->last_seen_at->diffForHumans() : NULL;
    }

    public function getRoleTitleAttribute()
    {
        return self::roles()[$this->role] ?? '';
    }

    public function getStatusTitleAttribute()
    {
        return self::statuses()[$this->status] ?? '';
    }
}
