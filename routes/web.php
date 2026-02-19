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

Route::get('/products', [ProductController::class, 'index'])->name('product.index');

Route::get('/start-order', [OrderStartController::class, 'index'])->name('order.start');
Route::post('/order/type', [OrderStartController::class, 'setType'])->name('order.setType');
Route::get('/cart',[CartController::class,'index']);
Route::post('/cart/add',[CartController::class,'add']);
Route::post('/checkout',[CartController::class,'checkout']);
