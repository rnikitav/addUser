<?php

namespace App\Http\Requests\Api\v1\AuthController;

use App\Http\Requests\Api\v1\APIFormRequest;

/**
 * @property string login
 * @property string password
 */
class AuthLoginRequest extends APIFormRequest
{

    public function rules(): array
    {
        return [
            'login'            => [
                'required',
                'string',
            ],
            'password'         => [
                'required',
                'string'
            ],
        ];
    }
}
