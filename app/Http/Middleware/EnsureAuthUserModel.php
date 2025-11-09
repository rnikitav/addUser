<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthUserModel
{

    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user() instanceof User) {
            abort(Response::HTTP_BAD_REQUEST);
        }

        return $next($request);
    }
}
