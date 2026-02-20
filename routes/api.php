<?php

use Illuminate\Support\Facades\Route; // PERBAIKAN: Gunakan Facade Laravel
use App\Http\Controllers\PaymentCallbackController;

// Route untuk menerima notifikasi otomatis dari Midtrans
Route::post('/midtrans-callback', [PaymentCallbackController::class, 'callback']);