<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
{
    $token = $request->header('Authorization');

    if (!$token) {
        return response()->json(['message' => 'Token missing'], 401);
    }

    $user = User::where('authtoken', $token)->first();

    if (!$user) {
        return response()->json(['message' => 'Invalid token'], 401);
    }

    $request->merge(['auth_user' => $user]);

    return $next($request);
}

}
