<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/tables/', [DashboardController::class, 'index'])->name('tables');
Route::get('/billing/', [DashboardController::class, 'index'])->name('billing');
Route::get('/virtual-reality/', [DashboardController::class, 'index'])->name('virtual-reality');
Route::get('/rtl/', [DashboardController::class, 'index'])->name('rtl');
Route::get('/notifications/', [DashboardController::class, 'index'])->name('notifications');
Route::get('/profile/', [DashboardController::class, 'index'])->name('profile');
Route::get('/static-sign-in/', [DashboardController::class, 'index'])->name('static-sign-in');
Route::get('/static-sign-up/', [DashboardController::class, 'index'])->name('static-sign-up');
Route::get('/logout/', [DashboardController::class, 'index'])->name('logout');
Route::get('/login/', [DashboardController::class, 'index'])->name('login');
Route::get('/register/', [DashboardController::class, 'index'])->name('register');
Route::get('/user-profile/', [ProfileController::class, 'index'])->name('user-profile');
Route::get('/user-management/', [ProfileController::class, 'index'])->name('user-management');
