<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // ログインユーザーの役割に基づいてリダイレクト
        $user = Auth::user();

        switch ($user->role->name) {
            case 'SuperAdmin':
                return redirect()->route('dashboard.superadmin');
            case 'Admin':
                return redirect()->route('dashboard.admin');
            case 'Manager':
                return redirect()->route('dashboard.manager');
            case 'User':
                return redirect()->route('dashboard.user');
            default:
                return redirect('/unauthorized');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
