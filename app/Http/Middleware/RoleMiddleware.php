<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // ユーザーが認証されていない場合
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 403);
        }

        // ユーザーにロールが設定されていない場合
        if (!$user->role) {
            return response()->json(['error' => 'User has no role'], 403);
        }

        // ユーザーのロールが許可されたロールに含まれていない場合
        if (!in_array($user->role->name, $roles)) {
            return response()->json([
                'error' => 'Role not authorized',
                'user_role' => $user->role->name,
                'allowed_roles' => $roles,
            ], 403);
        }

        // 条件をすべて満たしている場合
        return $next($request);
    }
}
