<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // ユーザーがログインしていない場合
        if (!Auth::check()) {
            return redirect('/login'); // ログインページにリダイレクト
        }

        // ユーザーの役割が一致しない場合
        if (Auth::user()->role->name !== $role) {
            return redirect('/unauthorized'); // 権限がないページにリダイレクト
        }

        // 次のリクエスト処理に進む
        return $next($request);
    }
}
