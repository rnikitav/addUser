<?php

namespace App\Http\Requests\Api\v1\UserBalanceTransactionController;


use App\Enums\UserBalanceTransactionTypeEnum;
use App\Http\Requests\Api\v1\APIFormRequest;
use Illuminate\Validation\Rule;

class UserBalanceTransactionIndexRequest extends APIFormRequest
{

    public function rules(): array
    {
        return [
            'per_page'          => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
            ],
            'type'              => [
                'nullable',
                'string',
                Rule::in(UserBalanceTransactionTypeEnum::getValues()),
            ],
            'date_from'         => [
                'nullable',
                'date'
            ]
        ];

    }

}