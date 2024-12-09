<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['role:SuperAdmin'])->group(function () {
    Route::get('/superadmin', [DashboardController::class, 'superAdmin'])->name('dashboard.superadmin');
});

Route::middleware(['role:Admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
});

Route::middleware(['role:Manager'])->group(function () {
    Route::get('/manager', [DashboardController::class, 'manager'])->name('dashboard.manager');
});

Route::middleware(['role:User'])->group(function () {
    Route::get('/user', [DashboardController::class, 'user'])->name('dashboard.user');
});

Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');
require __DIR__.'/auth.php';
