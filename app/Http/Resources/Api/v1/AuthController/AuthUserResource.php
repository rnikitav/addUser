<?php


namespace App\Http\Resources\Api\v1\AuthController;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class AuthUserResource extends JsonResource
{
    public function toArray($request): array
    {
        $resource = $this->resource;


        return [
            'id'                    => $resource->id,
            'email'                 => $resource->email,
            'login'                 => $resource->login,
            'balance'               => number_format($resource->balance->balance, 2, '.', ' '),
            'balanceTransactions'   => UserBalanceTransactionResource::collection($resource->balanceTransactions()->limit(5)->orderByDesc('id')->get()),
            'token'                 => $resource->createToken('token', ['*'] , now()->addDays(7))->plainTextToken,
        ];
    }
}
