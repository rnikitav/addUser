<?php

namespace App\Services\v1;


use App\Enums\UserBalanceTransactionTypeEnum;
use App\Models\Balance\UserBalance;
use App\Models\Balance\UserBalanceTransaction;
use App\Models\User;
use App\Utils\DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class UserBalanceTransactionService
{

    /**
     * @throws Throwable
     */
    public static function handleTransaction(
        User $user,
        float $amount,
        UserBalanceTransactionTypeEnum $type,
        string $description = null,
        bool $allowNegative = false
    ): UserBalanceTransaction
    {
        return DB::inTransaction(function () use ($user, $amount, $type, $description, $allowNegative) {
            /** @var UserBalance $balance */
            $balance = $user->balance()->lockForUpdate()->firstOrFail();
            $currentBalance = $balance->balance;

            $amount = abs($amount);

            // Рассчитываем новый баланс
            $newBalance = $type === UserBalanceTransactionTypeEnum::CREDIT
                ? $currentBalance + $amount
                : $currentBalance - $amount;

            // Проверяем отрицательный баланс
            if ($newBalance < 0 && !$allowNegative) {
                throw new Exception("Недостаточно средств на балансе");
            }

            $balance->balance = $newBalance;
            $balance->save();

            return $user->balanceTransactions()->forceCreate([
                'type'          => $type,
                'amount'        => $amount,
                'balance_after' => $newBalance,
                'description'   => $description,
            ]);
        });
    }
    public static function search(array $validated): Builder
    {
        $validated = (object)$validated;
        $builder = UserBalanceTransaction::query();

        $builder
            ->when(true,
                fn($q) => self::getUserBalanceTransactions($builder, $validated)
            )
            ->orderByDesc('id');

        return $builder;

    }


    public static function getUserBalanceTransactions(Builder $builder, object $validated): Builder
    {
        $builder
            ->when(data_get($validated, 'user_id'), fn($q) => $q->where('user_id', $validated->user_id))
            ->when(data_get($validated, 'type'), fn($q) => $q->where('type', $validated->type));

        return $builder;
    }

}