<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderStartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tables/{table}/print', [TableController::class, 'print'])
    ->name('tables.print');

Route::get('/menu', [ProductController::class, 'index'])->name('menu.index');

// ✅ START ORDER
Route::get('/start-order', [OrderStartController::class, 'index'])->name('order.start');
Route::post('/order/type', [OrderStartController::class, 'setType'])->name('order.setType');

// ✅ CART SYSTEM
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/finish', [CartController::class, 'clearAndFinish'])->name('cart.clear_and_finish');
Route::post('/cart/cancel-order', [CartController::class, 'cancelOrder'])->name('cart.cancel_order');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
// ✅ ORDER PAGE
Route::get('/order', function (Illuminate\Http\Request $request) {
    return view('order', [
        'type' => $request->type,
        'table' => $request->table
    ]);
});

// routes/web.php
Route::get('/order/{id}/print', function ($id) {
    $order = \App\Models\Order::with('items')->findOrFail($id);
    return view('order.print', compact('order'));
})->name('order.print');