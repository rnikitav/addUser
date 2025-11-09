<?php

namespace App\Models\Balance;

use App\Enums\UserBalanceTransactionTypeEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property integer id
 * @property integer user_id
 * @property UserBalanceTransactionTypeEnum type
 * @property float amount
 * @property float balance_after
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 *
 * @property User user
 */
class UserBalanceTransaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id'
    ];

    /**
     * Запрещаем массовое обновление баланса. Важное поле
     * Должно меняться только программно
     */
    protected $guarded = [
        'amount',
        'balance_after'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'type' => UserBalanceTransactionTypeEnum::class,
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'description' => 'string',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
