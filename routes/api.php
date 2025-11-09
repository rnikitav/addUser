<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\UserBalanceTransactionController;
use App\Http\Middleware\EnsureAuthUserModel;
use Illuminate\Support\Facades\Route;

Route::group(
    [
        'prefix'     => 'v1',
        'as'         => 'v1.',
        'middleware' => [],
    ],
    function () {
        App::setLocale('ru');

        //Not Auth group
        Route::post('/login', [AuthController::class, 'handle'])
            ->name('login');



        // Auth group
        Route::middleware(['auth:sanctum'])->group(function () {

            //me
            Route::prefix('me')->middleware(EnsureAuthUserModel::class)->group(function () {
                Route::get('balance-transactions', [UserBalanceTransactionController::class, 'index']);
            });

        });
    });
