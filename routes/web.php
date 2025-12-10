<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Route d'accueil
Route::get('/', function () {
    return view('users.index');
});

Route::get('/paiement', [PaymentController::class, 'create'])->name('payment.create');

// Routes de paiement
Route::prefix('paiement')->name('payment.')->group(function () {
    // Page de paiement
    Route::get('/', [PaymentController::class, 'create'])->name('create');
    
    // Traitement du paiement
    Route::post('/process', [PaymentController::class, 'process'])->name('process');
    
    // Confirmation avec redirection
    Route::get('/confirm/{id}', [PaymentController::class, 'confirm'])->name('confirm');
    
    // Callback FedaPay (retour après paiement)
    Route::get('/callback/{token}/{status}', [PaymentController::class, 'callback'])
        ->name('callback');
    
    // Pages de résultat
    Route::get('/success/{id}', [PaymentController::class, 'success'])->name('success');
    Route::get('/failed/{id}', [PaymentController::class, 'failed'])->name('failed');
    
    // Vérification AJAX du statut
    Route::get('/check-status/{id}', [PaymentController::class, 'checkStatus'])
        ->name('check-status');
});

// Accepte GET et POST pour le webhook
Route::match(['GET', 'POST'], '/fedapay/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Gardez la route de test séparée
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

// Routes d'administration des paiements
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/dashboard', [PaymentController::class, 'dashboard'])->name('payments.dashboard');
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/user/{userId?}', [PaymentController::class, 'userPayments'])->name('payments.user');
    Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('/payments/search', [PaymentController::class, 'search'])->name('payments.search');
});

require __DIR__.'/auth.php';
