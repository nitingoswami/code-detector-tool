<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->user_role !== 'admin') {
            return redirect()->route('admin.login')->withErrors(['email' => 'Unauthorized access']);
        }

        return $next($request);
    }
}
