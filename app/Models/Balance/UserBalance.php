<?php

namespace App\Models\Balance;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property integer id
 * @property integer user_id
 * @property double balance
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property User user
 */
class UserBalance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id'
    ];

    /**
     * Запрещаем массовое обновление amount. Важное поле
     * Должно меняться только программно
     */
    protected $guarded = [
        'balance'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'balance' => 'decimal:2',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
