<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesCourseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\OrderDataController;
use App\Http\Controllers\BackLogController;
use App\Http\Controllers\DailyReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SettingController;

// ← これを追加

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
    // SuperAdmin & Admin のルート
    Route::middleware('role:SuperAdmin,Admin')->group(function () {
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
    });

    // SuperAdmin 専用ルート
    Route::middleware('role:SuperAdmin')->group(function () {
        Route::get('/superadmin', [DashboardController::class, 'superAdmin'])->name('dashboard.superadmin');
    });

    // Admin 専用ルート
    Route::middleware('role:Admin')->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    });

    // Manager 専用ルート
    Route::middleware('role:Manager')->group(function () {
        Route::get('/manager', [DashboardController::class, 'manager'])->name('dashboard.manager');
    });

    // User 専用ルート
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

// 一般ユーザー用ルート
Route::middleware(['auth', 'role:SuperAdmin,Admin,Manager'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
});

// SuperAdmin & Admin 用ルート
// ユーザー管理ルート（SuperAdmin & Admin 用）
Route::middleware(['auth', 'role:SuperAdmin,Admin'])->group(function () {
    Route::resource('users', UserController::class); // ✅ これを追加

    // SalesCourse ルート
    Route::get('/salescourses/upload', [SalesCourseController::class, 'showUploadForm'])->name('salescourses.upload');
    Route::post('/salescourses/upload', [SalesCourseController::class, 'uploadCSV'])->name('salescourses.upload.post');
    Route::resource('salescourses', SalesCourseController::class);

    // Customer ルート
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/upload', [CustomerController::class, 'showUploadForm'])->name('customers.upload');
    Route::post('/customers/upload', [CustomerController::class, 'uploadCSV'])->name('customers.upload.post');
    Route::post('/customers/request-delete', [CustomerController::class, 'requestDelete'])->name('customers.request-delete');
    Route::post('/customers/confirm-delete', [CustomerController::class, 'confirmDelete'])->name('customers.confirm-delete');
    Route::get('/customers/delete-progress', [CustomerController::class, 'deleteProgress'])->name('customers.delete-progress');

    // SalesCourse 削除ルート
    Route::post('/salescourses/request-delete', [SalesCourseController::class, 'requestDelete'])->name('salescourses.request-delete');
    Route::post('/salescourses/confirm-delete', [SalesCourseController::class, 'confirmDelete'])->name('salescourses.confirm-delete');

    // Commodity ルート
    Route::get('/commodities', [CommodityController::class, 'index'])->name('commodities.index');
    Route::get('/commodities/upload', [CommodityController::class, 'showUploadForm'])->name('commodities.upload.form');
    Route::post('/commodities/upload', [CommodityController::class, 'upload'])->name('commodities.upload');
    Route::post('/commodities/request-delete', [CommodityController::class, 'requestDelete'])->name('commodities.request-delete');
    Route::post('/commodities/confirm-delete', [CommodityController::class, 'confirmDelete'])->name('commodities.confirm-delete');

    // OrderData ルート
    Route::get('/orderdata', [OrderDataController::class, 'index'])->name('orderdata.index');
    Route::get('/orderdata/upload', [OrderDataController::class, 'showUploadForm'])->name('orderdata.upload.form');
    Route::post('/orderdata/upload', [OrderDataController::class, 'upload'])->name('orderdata.upload');
    Route::post('/orderdata/request-delete', [OrderDataController::class, 'requestDelete'])->name('orderdata.request-delete');
    Route::post('/orderdata/confirm-delete', [OrderDataController::class, 'confirmDelete'])->name('orderdata.confirm-delete');

    // BackLog ルート
    Route::get('/backlogs', [BackLogController::class, 'index'])->name('backlogs.index');
    Route::get('/backlogs/upload', [BackLogController::class, 'showUploadForm'])->name('backlogs.upload');
    Route::post('/backlogs/upload', [BackLogController::class, 'upload'])->name('backlogs.upload.post');
    Route::post('/backlogs/request-delete', [BackLogController::class, 'requestDelete'])->name('backlogs.request-delete');
    Route::post('/backlogs/confirm-delete', [BackLogController::class, 'confirmDelete'])->name('backlogs.confirm-delete');

    // ✅ `daily_reports` の RESTful ルートを設定
    Route::resource('daily_reports', DailyReportController::class);

    Route::post('/daily-reports/{report}/read', [DailyReportController::class, 'markAsRead'])->name('daily_reports.read');
    Route::post('/daily-reports/{report}/comment', [DailyReportController::class, 'addComment'])->name('daily_reports.comment');
    Route::post('/daily_reports/{id}/comment', [DailyReportController::class, 'addComment'])->name('daily_reports.comment');

    Route::patch('/daily_reports/{id}/comment/{commentIndex}', [DailyReportController::class, 'updateComment'])->name('daily_reports.updateComment');
    Route::delete('/daily_reports/{id}/comment/{commentIndex}', [DailyReportController::class, 'deleteComment'])->name('daily_reports.deleteComment');
    Route::get('/alert', [SettingController::class, 'alert'])->name('alert.index');
    Route::post('/alert', [SettingController::class, 'updateAlert'])->name('alert.update');
});


Route::get('/test-log', function () {
    Log::error('テストログ: これは動作しているか？');
    return 'ログを確認してください';
});
