<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Route d'accueil
Route::get('/', function () {
    return view('users.index');
});

Route::get('/paiement', [PaymentController::class, 'create'])->name('payment.create');


Route::prefix('paiement')->name('payment.')->group(function () {

    Route::get('/', [PaymentController::class, 'create'])->name('create');
    
    Route::post('/process', [PaymentController::class, 'process'])->name('process');
    
    Route::get('/confirm/{id}', [PaymentController::class, 'confirm'])->name('confirm');
    
    Route::get('/callback/{token}/{status}', [PaymentController::class, 'callback'])
        ->name('callback');
    
    Route::get('/success/{id}', [PaymentController::class, 'success'])->name('success');
    Route::get('/failed/{id}', [PaymentController::class, 'failed'])->name('failed');
    
    Route::get('/check-status/{id}', [PaymentController::class, 'checkStatus'])
        ->name('check-status');
});


Route::get('/fedapay/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/fedapay/webhook-test', [PaymentController::class, 'webhookTest'])
    ->name('payment.webhook-test');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';