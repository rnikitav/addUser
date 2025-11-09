<?php

namespace App\Jobs;

use App\Enums\UserBalanceTransactionTypeEnum;
use App\Http\Middleware\RetryOnDeadlockMiddleware;
use App\Models\User;
use App\Services\v1\UserBalanceTransactionService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessBalanceTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5; // до 5 попыток
    public int $timeout = 30;

    public function __construct(
        public int $userId,
        public float $amount,
        public UserBalanceTransactionTypeEnum $type,
        public ?string $description = null,
        public bool $allowNegative = false
    ) {
        // Отправляем эту задачу в отдельную очередь
        $this->onQueue('balance');
    }

    public function middleware(): array
    {
        return [
            new RetryOnDeadlockMiddleware()
        ];
    }

    /**
     * @throws Throwable
     */
    public function handle(): void
    {
        $user = User::query()->find($this->userId);

        if (!$user) {
            throw new Exception("Юзер Ид - $this->userId не найден");
        }

        try {
            UserBalanceTransactionService::handleTransaction(
                $user,
                $this->amount,
                $this->type,
                $this->description,
                $this->allowNegative
            );
        } catch (Exception $e) {
            if (str_contains($e->getMessage(), 'DB Exception')) {
                // Логируем и не кидаем дальше → Job не будет повторяться
                Log::warning("Баланс пользователя слишком мал: $user->id");
                return;
            }
            throw $e; // другие ошибки кидаем
        }

    }
}
