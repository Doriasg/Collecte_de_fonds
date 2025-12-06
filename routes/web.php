<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoutientController;

Route::get('/', function () {
    return view('users.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/form_soutient', function () {
    return view('form_soutient');
})->name('form_soutient');

Route::middleware(['web'])->group(function () {
    Route::get('/soutenir', [SoutientController::class, 'showPaymentForm'])->name('soutenir');
    Route::post('/process-payment', [SoutientController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/callback', [SoutientController::class, 'paymentCallback'])->name('payment.callback');
    Route::post('/payment/webhook', [SoutientController::class, 'paymentWebhook'])->name('payment.webhook');
});


require __DIR__.'/auth.php';
