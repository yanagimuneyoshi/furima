<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return redirect('/'); // 管理者でない場合はホームページにリダイレクト
        }

        return $next($request);
    }
}
