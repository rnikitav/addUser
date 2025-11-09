<?php


namespace App\Http\Resources\Api\v1\UserBalanceTransactionController;

use App\Models\Balance\UserBalanceTransaction;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property UserBalanceTransaction $resource
 */
class UserBalanceTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        $resource = $this->resource;

        return [
            'id'                => $resource->id,
            'type'              => $resource->type,
            'type_translate'    => $resource->type->translate(),
            'amount'            => $resource->amount,
            'description'       => $resource->description,
            'balance_after'     => $resource->balance_after,
            'created_at'        => $resource->created_at,
        ];
    }
}
