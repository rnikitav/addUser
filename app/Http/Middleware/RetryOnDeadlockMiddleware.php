<?php

namespace App\Http\Middleware;

use Illuminate\Support\Str;
use Throwable;

class RetryOnDeadlockMiddleware
{
    /**
     * @throws Throwable
     */
    public function handle($job, $next): void
    {
        try {
            $next($job);

        } catch (Throwable $e) {

            $message = $e->getMessage();

            // Deadlock или lock wait timeout
            if (
                Str::contains($message, 'Deadlock') ||
                Str::contains($message, 'deadlock') ||
                Str::contains($message, 'Lock wait timeout') ||
                Str::contains($message, 'lock wait timeout')
            ) {
                // Повторяем задачу
                $job->release(2); // повтор через 2 секунды
                return;
            }

            throw $e; // остальные ошибки — дальше
        }
    }
}
