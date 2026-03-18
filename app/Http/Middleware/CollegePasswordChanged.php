<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CollegePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $college = auth()->guard('college')->user();

        if ($college && !$college->password_changed) {
            if (!$request->routeIs('college.password.change') && !$request->routeIs('college.password.update')) {
                return redirect()->route('college.password.change');
            }
        }

        return $next($request);
    }
}
