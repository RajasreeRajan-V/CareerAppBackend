<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json([
                "status" => "0",
                "status_code" => "401",
                "data" => (object)[],
                "message" => "Authorization token missing"
            ], 401);
        }

        $token = str_replace('Bearer ', '', $header);

        $user = User::where('auth_token', $token)->first();

        if (!$user) {
            return response()->json([
                "status" => "0",
                "status_code" => "401",
                "data" => (object)[],
                "message" => "Invalid or expired token"
            ], 401);
        }

        // Attach user to request if needed later
        $request->authUser = $user;

        return $next($request);
    }
}
