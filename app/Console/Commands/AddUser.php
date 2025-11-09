<?php

namespace App\Console\Commands;

use App\Models\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Command\Command as CommandAlias;

class AddUser extends Command
{

    protected $signature = 'user:add 
                            {login? : Логин пользователя}
                            {email? : Email пользователя}
                            {--password= : Пароль пользователя}';
    protected $description = 'Команда добавления юзера user:add {login} {email} {password}';


    public function handle(): int
    {
        $login = $this->checkUnique('Введите имя пользователя', 'login');
        $email = $this->checkUnique('Введите email', 'email',
                fn($value) => filter_var($value, FILTER_VALIDATE_EMAIL) !== false
            );
        $password = $this->option('password') ?? $this->secret('Введите пароль');


        // Валидация данных
        $validator = Validator::make([
            'login' => $login,
            'email' => $email,
            'password' => $password,
        ], [
            'login' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
        ]);

        if ($validator->fails()) {
            $this->error('Ошибка валидации:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- ' . $error);
            }
            return CommandAlias::FAILURE;
        }

        // Создаем пользователя
        try {
            $user = User::query()->create([
                'login' => $login,
                'email' => $email,
                'password' => Hash::make($password),
            ]);


            $this->info('✓ Пользователь успешно создан!');
            $this->newLine();

            // Выводим информацию о созданном пользователе
            $this->table(
                ['ID', 'Логин', 'Email'],
                [[$user->id, $user->login, $user->email]]
            );

            return CommandAlias::SUCCESS;
        } catch (Exception $e) {
            $this->error('Ошибка при создании пользователя: ' . $e->getMessage());
            return CommandAlias::FAILURE;
        }
    }


    protected function checkUnique(string $prompt, string $column, callable $validator = null, int $maxAttempts = 3): string
    {
        $attempts = 0;

        $argument = $this->argument($column);

        while ($attempts < $maxAttempts) {

            $value = $argument ?? $this->ask($prompt);

            //если вводился аргумент сбрасываем
            $argument = null;

            // Проверка пустого значения
            if (empty($value)) {
                $this->error('Значение не может быть пустым.');
                $attempts++;
                continue;
            }

            // Проверка дополнительным валидатором
            if ($validator && !$validator($value)) {
                $this->error('Неверный формат значения. Попробуйте ещё раз.');
                $attempts++;
                continue;
            }

            // Проверка уникальности в базе
            if (User::query()->where($column, $value)->exists()) {
                $this->error("Пользователь с таким $column уже существует. Попробуйте другое значение.");
                $attempts++;
                continue;
            }

            return $value;
        }

        $this->error("Превышено количество попыток ввода $column.");
        exit(CommandAlias::FAILURE);
    }
}
