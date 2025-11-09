<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\UserBalanceTransactionController\UserBalanceTransactionIndexRequest;
use App\Http\Resources\Api\v1\UserBalanceTransactionController\UserBalanceTransactionResource;
use App\Services\v1\UserBalanceTransactionService;

class UserBalanceTransactionController extends Controller
{
    public function index(UserBalanceTransactionIndexRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();
        $validated['user_id'] = $user->id;

        $data = UserBalanceTransactionService::search($validated)->paginate($validated['per_page'] ?? 5);
        return UserBalanceTransactionResource::collection($data);

    }

}