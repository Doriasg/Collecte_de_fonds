<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\indexController;
use Illuminate\Support\Facades\Route;

// Route d'accueil

Route::get('/', [indexController::class, 'index'])->name('home');
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

Route::prefix('paiement')->name('payment.')->group(function () {
    Route::get('/waiting/{id}', [PaymentController::class, 'waiting'])->name('waiting');
});

// Accepte GET et POST pour le webhook
Route::match(['GET', 'POST'], '/fedapay/webhook', [PaymentController::class, 'webhook'])
    ->name('payment.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Gardez la route de test séparée
Route::get('/fedapay/webhook-test', [PaymentController::class, 'webhookTest'])
    ->name('payment.webhook-test');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [PaymentController::class, 'dashboard'])->name('dashboard');
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
    // Routes statiques AVANT la route dynamique {id}
    Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');
    Route::get('/payments/search', [PaymentController::class, 'search'])->name('payments.search');
    Route::get('/payments/user/{userId?}', [PaymentController::class, 'userPayments'])->name('payments.user');
    // Route dynamique en dernier
    Route::get('/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
    // Remboursement
    Route::post('/payments/{id}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    // Notes
    Route::post('/payments/{id}/notes', [PaymentController::class, 'updateNotes'])->name('payments.notes');
    // Renvoyer reçu WhatsApp
    Route::post('/payments/{id}/resend-receipt', [PaymentController::class, 'resendReceipt'])->name('payments.resend-receipt');
});

// ===== TÉLÉCHARGEMENT REÇU PAR RÉFÉRENCE (cible du QR code) =====
Route::get('/recu/{reference}', function (string $reference) {
    $payment = \App\Models\Payment::where('reference', $reference)
        ->where('status', 'approved')
        ->firstOrFail();

    $receiptService = app(\App\Services\ReceiptService::class);
    $pdfContent = $receiptService->generatePdf($payment);

    return response($pdfContent, 200, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="recu_mission_sourire_' . $reference . '.pdf"',
    ]);
})->name('receipt.download');

// ===== PRÉVISUALISATION PDF (développement uniquement) =====
Route::get('/dev/pdf-preview', function () {
    $tz   = new \DateTimeZone('Africa/Porto-Novo');
    $date = (new \DateTime('now', $tz))->format('d/m/Y - H:i');
    $ref  = 'trx_demo_' . strtolower(substr(md5('preview'), 0, 12));

    $payment = new \App\Models\Payment([
        'id'             => 99999,
        'transaction_id' => 'TRX-DEMO-20261201',
        'reference'      => $ref,
        'amount'         => 25000,
        'currency'       => 'XOF',
        'status'         => 'approved',
        'payment_method' => 'mtn',
        'customer_email' => 'jean.dupont@example.com',
        'customer_phone' => '+229 97 00 00 00',
        'metadata'       => ['firstname' => 'Jean', 'lastname' => 'DUPONT'],
        'paid_at'        => now(),
    ]);

    $svc         = app(\App\Services\ReceiptService::class);
    $pdfUrl      = url('/recu/' . $ref);
    $qrBase64    = $svc->generateQrBase64($pdfUrl);
    $logoIwa     = $svc->imageToBase64(public_path('images/2-1.png'));
    $logoDglink  = $svc->imageToBase64(public_path('images/DGlink.png'));
    $logoChorale = $svc->imageToBase64(public_path('images/chorale.jpg'));

    return view('pdf.receipt', [
        'payment'      => $payment,
        'firstname'    => 'Jean',
        'lastname'     => 'DUPONT',
        'reference'    => $ref,
        'amount'       => number_format(25000, 0, ',', ' '),
        'currency'     => 'XOF',
        'date'         => $date,
        'method'       => 'MTN MOBILE MONEY',
        'email'        => 'jean.dupont@example.com',
        'phone'        => '+229 97 00 00 00',
        'qr_base64'    => $qrBase64,
        'logo_iwa'     => $logoIwa,
        'logo_dglink'  => $logoDglink,
        'logo_chorale' => $logoChorale,
        'pdf_url'      => $pdfUrl,
    ]);
})->name('dev.pdf.preview');

Route::get('/dev/pdf-download', function () {
    $tz   = new \DateTimeZone('Africa/Porto-Novo');
    $date = (new \DateTime('now', $tz))->format('d/m/Y - H:i');
    $ref  = 'trx_demo_' . strtolower(substr(md5('preview'), 0, 12));

    $payment = new \App\Models\Payment([
        'id'             => 99999,
        'transaction_id' => 'TRX-DEMO-20261201',
        'reference'      => $ref,
        'amount'         => 25000,
        'currency'       => 'XOF',
        'status'         => 'approved',
        'payment_method' => 'mtn',
        'customer_email' => 'jean.dupont@example.com',
        'customer_phone' => '+229 97 00 00 00',
        'metadata'       => ['firstname' => 'Jean', 'lastname' => 'DUPONT'],
        'paid_at'        => now(),
    ]);

    $svc         = app(\App\Services\ReceiptService::class);
    $pdfUrl      = url('/recu/' . $ref);
    $qrBase64    = $svc->generateQrBase64($pdfUrl);
    $logoIwa     = $svc->imageToBase64(public_path('images/2-1.png'));
    $logoDglink  = $svc->imageToBase64(public_path('images/DGlink.png'));
    $logoChorale = $svc->imageToBase64(public_path('images/chorale.jpg'));

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.receipt', [
        'payment'      => $payment,
        'firstname'    => 'Jean',
        'lastname'     => 'DUPONT',
        'reference'    => $ref,
        'amount'       => number_format(25000, 0, ',', ' '),
        'currency'     => 'XOF',
        'date'         => $date,
        'method'       => 'MTN MOBILE MONEY',
        'email'        => 'jean.dupont@example.com',
        'phone'        => '+229 97 00 00 00',
        'qr_base64'    => $qrBase64,
        'logo_iwa'     => $logoIwa,
        'logo_dglink'  => $logoDglink,
        'logo_chorale' => $logoChorale,
        'pdf_url'      => $pdfUrl,
    ])->setPaper('a4', 'portrait')
      ->setOption('defaultFont', 'DejaVu Sans')
      ->setOption('isHtml5ParserEnabled', true)
      ->setOption('dpi', 150);

    $output = $pdf->stream('recu_mission_sourire_demo.pdf');

    // Nettoyer le QR temporaire
    if ($qrPath && file_exists($qrPath)) @unlink($qrPath);

    return $output;
})->name('dev.pdf.download');

require __DIR__.'/auth.php';
