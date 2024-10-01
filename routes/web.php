<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('welcome');
})->name('Home');

Route::get('login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('login', [UserController::class, 'login'])->name('login.submit');
Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('dashboard', [UserController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'isAdmin:admin,warehouse_manager,cashier,procurement_officer,supplier'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('transactions', TransactionController::class);

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])
        ->name('orders.show');
    Route::post('/orders/{order}/approve', [OrderController::class, 'approveOrder'])->name('orders.approve');
    Route::post('/orders/{order}/disapprove', [OrderController::class, 'disapproveOrder'])->name('orders.disapprove');
    Route::post('/orders/{order}/accept', [OrderController::class, 'acceptOrderBySupplier'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [OrderController::class, 'rejectOrderBySupplier'])->name('orders.reject');
    Route::post('/orders/{order}/ship', [OrderController::class, 'supplierShipOrder'])->name('orders.ship');
    Route::post('/orders/{order}/delivered', [OrderController::class, 'markAsDelivered'])->name('orders.delivered');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
});

