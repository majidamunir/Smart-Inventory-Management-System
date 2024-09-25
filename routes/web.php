<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
})->name('Home');

Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login'])->name('login.submit');
Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'isAdmin:admin,warehouse_manager'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});

