<?php

namespace App\Models;

use App\Models\Balance\UserBalance;
use App\Models\Balance\UserBalanceTransaction;
use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property integer id
 * @property string login
 * @property string email
 * @property string password
 * @property string remember_token
 * @property Carbon email_verified_at
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property UserBalance balance
 * @property Collection<UserBalanceTransaction> balanceTransactions
 */
class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens;
    
    protected $fillable = [
        'login',
        'email',
        'password',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id'                => 'integer',
        'login'              => 'string',
        'email'             => 'string',
        'remember_token'    => 'string',
        'password'          => 'hashed'
    ];


    protected static function booted(): void
    {
        self::observe(UserObserver::class);
    }

    public function balance(): HasOne
    {
        return $this->hasOne(UserBalance::class);
    }
    public function balanceTransactions(): HasMany
    {
        return $this->hasMany(UserBalanceTransaction::class);
    }
}
