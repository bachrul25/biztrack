<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            return match ($user->role) {
                'admin' => redirect('/admin/dashboard'),
                'buyer' => redirect('/buyer/dashboard'),
                'seller' => redirect('/seller/dashboard'),
                default => redirect('/login'),
            };
        }

        return $next($request);
    }
}
