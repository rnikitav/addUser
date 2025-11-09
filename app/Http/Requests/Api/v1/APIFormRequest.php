<?php

namespace App\Http\Requests\Api\v1;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class APIFormRequest extends FormRequest
{
    /**
     * Формат вывода ошибок валидации для API
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json(
                [
                    'errors' => [
                        'fields' => $validator->errors(),
                    ],
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }
}
