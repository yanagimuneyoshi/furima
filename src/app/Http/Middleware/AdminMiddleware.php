<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        // ユーザーがログインしていないか、管理者でない場合は403を返す
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.'); // 403エラーを返す
        }

        return $next($request);
    }
}

