<?php

namespace App\Console\Commands;

use App\Enums\UserBalanceTransactionTypeEnum;
use App\Jobs\ProcessBalanceTransactionJob;
use App\Models\User;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

class AddBalanceTransaction extends Command
{
    protected $signature = 'balance-transaction:add
        {login : Логин пользователя}';

    protected $description = 'Добавление финансовой транзакции';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $login = $this->argument('login');

        $user = User::query()->where('login', $login)->first();

        if (!$user) {
            $this->error("Пользователь с логином '$login' не найден");
            return self::FAILURE;
        }

        $typeChoice = $this->choice(
            'Выберите тип операции',
            ['credit' => 'Пополнение', 'debit' => 'Списание'],
            0
        );

        $type = UserBalanceTransactionTypeEnum::from($typeChoice);



        // Ввод суммы с повторами
        try {
            $amount = $this->askAmountWithRetry();
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        // Проверка баланса до запуска Job
        if ($type === UserBalanceTransactionTypeEnum::DEBIT && $amount > $user->balance->balance) {
            $this->error('Недостаточно средств на балансе. Операция не будет выполнена.');
            return self::FAILURE;
        }

        $description  = $this->ask('Введите описание');
        try {
            ProcessBalanceTransactionJob::dispatch($user->id, $amount, $type, $description);

            $this->info('Заявка принята в обработку. Смотрите транзакции в личном кабинете');

            return self::SUCCESS;

        } catch (Throwable $e) {
        } {
            $this->error('Ошибка: ' . $e->getMessage());
            return self::FAILURE;
        }

    }


    private function askAmountWithRetry(int $maxAttempts = 3): float
    {
        $attempts = 0;

        while ($attempts < $maxAttempts) {

            $value = $this->ask('Введите сумму операции');

            // Проверка: пусто
            if (empty($value)) {
                $this->error('Сумма не может быть пустой.');
                $attempts++;
                continue;
            }

            // Проверка: числовое значение
            if (!is_numeric($value)) {
                $this->error('Введите числовое значение.');
                $attempts++;
                continue;
            }

            $amount = (float)$value;

            // Проверка: положительное число
            if ($amount <= 0) {
                $this->error('Сумма должна быть положительным числом.');
                $attempts++;
                continue;
            }

            return $amount;
        }

        // если попытки закончились
        throw new RuntimeException("Превышено количество попыток ввода суммы.");
    }
}
