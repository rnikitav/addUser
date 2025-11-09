<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\AuthController\AuthLoginRequest;
use App\Http\Resources\Api\v1\AuthController\AuthUserResource;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{

    /**
     * @throws AuthenticationException
     */
    public function handle(AuthLoginRequest $request)
    {
        $success = auth()->attempt([
            'login' => $request->login,
            'password' => $request->password
        ]);

        if($success) {
            return AuthUserResource::make(auth()->user());
        }

        throw new AuthenticationException('Неверный логин или пароль');

    }

}