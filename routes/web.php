<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SalesCourseController;
use App\Http\Controllers\CustomerController;


// 認証関連のルートをロード
require __DIR__ . '/auth.php';

// ホームページ
Route::get('/', function () {
    return redirect('/login');
});

// ダッシュボードリダイレクト
Route::get('/redirect', function () {
    $role = Auth::user()->role->name ?? null;

    $routes = [
        'SuperAdmin' => '/superadmin',
        'Admin' => '/admin',
        'Manager' => '/manager',
        'User' => '/user',
    ];


    return isset($routes[$role]) ? redirect($routes[$role]) : redirect('/unauthorized');
})->middleware('auth')->name('redirect');

// 認証後のデフォルトダッシュボードルート
Route::get('/dashboard', function () {
    return redirect()->route('redirect');
})->middleware(['auth', 'verified'])->name('dashboard');

// プロフィール関連のルート
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 各権限ごとのルート設定
Route::middleware(['auth'])->group(function () {
    // SuperAdmin ルート
    Route::middleware('role:SuperAdmin,Admin')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    Route::middleware('role:SuperAdmin')->group(function () {
        Route::get('/superadmin', [DashboardController::class, 'superAdmin'])->name('dashboard.superadmin');
    });

    // Admin ルート
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });

    // Manager ルート
    Route::middleware('role:Manager')->group(function () {
        Route::get('/manager', [DashboardController::class, 'manager'])->name('dashboard.manager');
    });

    // User ルート
    Route::middleware('role:User')->group(function () {
        Route::get('/user', [DashboardController::class, 'user'])->name('dashboard.user');
    });
});

// Unauthorized ページ
Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

// デバッグ用ルート
Route::get('/debug', function () {
    $user = auth()->user();
    return response()->json([
        'user' => $user,
        'role' => $user->role->name ?? 'No Role',
    ]);
})->middleware('auth');

Route::middleware(['auth', 'role:SuperAdmin,Admin,Manager'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

});

Route::middleware(['auth', 'role:SuperAdmin,Admin'])->group(function () {
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::get('/salescourses/upload', [SalesCourseController::class, 'showUploadForm'])->name('salescourses.upload');
    Route::post('/salescourses/upload', [SalesCourseController::class, 'uploadCSV'])->name('salescourses.upload.post');
    Route::resource('salescourses', SalesCourseController::class);

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/upload', [CustomerController::class, 'showUploadForm'])->name('customers.upload');
    Route::post('/customers/upload', [CustomerController::class, 'uploadCSV'])->name('customers.upload.post');

});
