<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use FedaPay\Error\InvalidRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;

class PaymentController extends Controller{
    public function create(){
        return view('payment.create');
    }

    public function process(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:100|max:10000000',
            'email' => 'required|email|max:150',
            'phone' => [
                'required',
                'regex:/^(0\d{9}|\d{9,13}|229\d{9,13}|\+229\d{9,13})$/'
            ],
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'description' => 'nullable|string|max:500'
        ], [
            'phone.regex' => 'Format téléphone invalide. Format: 0XXXXXXXXX (10 chiffres avec 0)'
        ]);

        if ($validator->fails()) {
            Log::error('Validation échouée', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        // FORMATAGE CORRIGÉ - Garder 10 chiffres pour le Bénin
        $formattedPhone = $this->formatPhoneForFedapay($request->phone);
        
        Log::info('Données formatées pour FedaPay', [
            'original_phone' => $request->phone,
            'formatted_phone' => $formattedPhone,
            'email' => $request->email,
            'amount' => $request->amount
        ]);

        try {
            // Initialisation FedaPay
            $this->initializeFedapay();
            
            // OPTION 1: Essayer avec customer complet
            try {
                $transaction = $this->createTransactionWithCustomer(
                    $request->amount,
                    $request->description,
                    $request->firstname,
                    $request->lastname,
                    $request->email,
                    $formattedPhone
                );
            } catch (InvalidRequest $e) {
                // OPTION 2: Si échec, essayer sans customer (FedaPay demandera les infos)
                Log::warning('Échec avec customer, tentative sans customer', [
                    'error' => $e->getMessage()
                ]);
                
                $transaction = $this->createTransactionWithoutCustomer(
                    $request->amount,
                    $request->description
                );
            }

            // Sauvegarder en base
            $payment = $this->savePayment($transaction, [
                'email' => $request->email,
                'phone' => $formattedPhone,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'description' => $request->description
            ]);

            return redirect()->route('payment.confirm', $payment->id);

        } catch (InvalidRequest $e) {
            $errorDetails = $this->parseFedapayError($e);
            
            Log::error('❌ Erreur FedaPay détaillée', [
                'message' => $e->getMessage(),
                'http_status' => $e->getHttpStatus(),
                'json_body' => $e->getJsonBody(),
                'error_type' => get_class($e)
            ]);
            
            return back()->withErrors([
                'fedapay_error' => $errorDetails['user_message']
            ])->withInput();
            
        } catch (\Exception $e) {
            Log::error('❌ Erreur générale', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'Une erreur est survenue: ' . $e->getMessage()
            ])->withInput();
        }
    }

    /**
     * Formatage téléphone pour FedaPay - CORRECTION IMPORTANTE
     * Format attendu: +229XXXXXXXXXX (12 chiffres au total)
     * Bénin: 10 chiffres (0XXXXXXXXX) -> +229XXXXXXXXX (13 caractères)
     */
    private function formatPhoneForFedapay($phone){
        // Nettoyer (garder chiffres uniquement)
        $phone = preg_replace('/[^0-9]/', '', $phone);

        Log::info('Formatage téléphone', ['input' => $phone]);

        // CAS 1 : 10 chiffres commençant par 0 (format local correct)
        if (preg_match('/^0\d{9}$/', $phone)) {
            Log::info('Format déjà local 10 chiffres', ['output' => $phone]);
            return $phone;
        }

        // CAS 2 : 9 chiffres (numéro sans zéro au début)
        if (preg_match('/^\d{9}$/', $phone)) {
            $result = '0' . $phone;
            Log::info('Format 9 chiffres -> 10 chiffres', [
                'output' => $result
            ]);
            return $result;
        }

        // CAS 3 : Format +229XXXXXXXXX (11 ou plus)
        if (preg_match('/^229(\d{9})$/', $phone, $m)) {
            $result = '0' . $m[1];
            Log::info('Format 229 -> local 10 chiffres', ['output' => $result]);
            return $result;
        }

        // CAS 4 : Format 229XXXXXXXXX (sans +)
        if (preg_match('/^229(\d{9})$/', $phone, $matches)) {
            $result = '0' . $matches[1];
            Log::info('Format 229 -> local 10 chiffres', ['output' => $result]);
            return $result;
        }

        throw new \Exception("Format téléphone invalide: $phone. Format attendu: 0XXXXXXXXX (10 chiffres)");
    }

    
    /**
     * Créer une transaction AVEC customer
     */
    private function createTransactionWithCustomer($amount, $description, $firstname, $lastname, $email, $phone){
        // Extraire le numéro sans +229
        $numberOnly = '+229' .$phone; // Ajouter "+229"
        
        Log::info('Création transaction avec customer', [
            'phone_full' => $numberOnly,
            'phone_number' => $phone,
            'phone_length' => strlen($phone)
        ]);
        
        return Transaction::create([
            'description' => $description ?? 'Paiement en ligne',
            'amount' => intval($amount),
            'currency' => ['iso' => 'XOF'],
            'callback_url' => route('payment.webhook'),
            'customer' => [
                'firstname' => substr($firstname, 0, 50),
                'lastname' => substr($lastname, 0, 50),
                'email' => $email,
                'phone_number' => [
                    'number' => $numberOnly,
                    'country' => 'BJ'
                ]
            ]
        ]);
    }
    
    /**
     * Créer une transaction SANS customer (fallback)
     * FedaPay demandera les infos lors du paiement
     */
    private function createTransactionWithoutCustomer($amount, $description){
        Log::info('Création transaction sans customer (fallback)');
        
        return Transaction::create([
            'description' => $description ?? 'Paiement en ligne',
            'amount' => intval($amount),
            'currency' => ['iso' => 'XOF'],
            'callback_url' => route('payment.webhook')
            // Pas de customer - FedaPay le demandera
        ]);
    }
    
    /**
     * Initialisation FedaPay
     */
    private function initializeFedapay()
    {
        $apiKey = config('services.fedapay.secret_key');
        $env = config('services.fedapay.environment', 'live');
        
        if (empty($apiKey)) {
            throw new \Exception('Clé API FedaPay non configurée');
        }
        
        Log::info('Initialisation FedaPay', [
            'environment' => $env,
            'key_prefix' => substr($apiKey, 0, 10) . '...',
            'key_type' => strpos($apiKey, 'sk_live_') === 0 ? 'LIVE' : 'TEST'
        ]);
        
        FedaPay::setApiKey($apiKey);
        FedaPay::setEnvironment($env);
        
        // Désactiver SSL verification en dev si besoin
        if (config('app.debug')) {
            FedaPay::setVerifySslCerts(false);
        }
    }
    
    /**
     * Sauvegarde du paiement
     */
    private function savePayment($transaction, $customerData){
        return Payment::create([
            'user_id' => null,
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
            'amount' => $transaction->amount,
            'currency' => 'XOF',
            'status' => $transaction->status,
            'payment_url' => $transaction->payment_url,
            'customer_email' => $customerData['email'],
            'customer_phone' => $customerData['phone'],
            'metadata' => [
                'firstname' => $customerData['firstname'],
                'lastname' => $customerData['lastname'],
                'description' => $customerData['description'] ?? null,
                'fedapay_transaction_id' => $transaction->id
            ]
        ]);
    }
    
    /**
     * Analyse des erreurs FedaPay
     */
    private function parseFedapayError($exception){
        $message = $exception->getMessage();
        $httpStatus = $exception->getHttpStatus();
        $jsonBody = $exception->getJsonBody() ?? [];
        
        Log::error('Détails erreur FedaPay', [
            'raw_message' => $message,
            'http_status' => $httpStatus,
            'json_body' => $jsonBody
        ]);
        
        $userMessage = 'Erreur lors du traitement du paiement';
        
        // Vérifier le body JSON pour plus de détails
        if (isset($jsonBody['errors'])) {
            $errors = $jsonBody['errors'];
            
            if (is_array($errors)) {
                foreach ($errors as $error) {
                    if (isset($error['message'])) {
                        $userMessage = $error['message'];
                        break;
                    }
                }
            }
        }
        
        // Messages spécifiques
        if (str_contains($message, 'phone') || str_contains($userMessage, 'phone')) {
            $userMessage = 'Numéro de téléphone invalide. Format: 0XXXXXXXXX (10 chiffres avec 0)';
        }
        
        if (str_contains($message, 'customer')) {
            $userMessage = 'Erreur création profil. Essayez à nouveau.';
        }
        
        if ($httpStatus === 401) {
            $userMessage = 'Clé API invalide. Contactez l\'administrateur.';
        }
        
        if ($httpStatus === 402) {
            $userMessage = 'Paiement requis. Vérifiez votre compte FedaPay.';
        }
        
        return [
            'user_message' => $userMessage,
            'technical_message' => $message,
            'http_status' => $httpStatus,
            'json_body' => $jsonBody
        ];
    }

    public function confirm($id)
    {
        $payment = Payment::findOrFail($id);
        
        return view('payment.confirm', compact('payment'));
    }

    // Vérifier manuellement le statut
    public function checkStatus($id)
    {
        $payment = Payment::findOrFail($id);

        try {
            FedaPay::setApiKey(config('services.fedapay.secret_key'));
            
            $transaction = Transaction::retrieve($payment->transaction_id);
            
            // Mettre à jour le statut
            $payment->update([
                'status' => $transaction->status
            ]);

            if ($transaction->status === 'approved') {
                $payment->markAsPaid($transaction->mode);
            }

            return response()->json([
                'status' => $payment->status,
                'is_successful' => $payment->isSuccessful(),
                'payment_url' => $payment->payment_url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Page de succès
    public function success($id)
    {
        $payment = Payment::findOrFail($id);
        
        if (!$payment->isSuccessful()) {
            return redirect()->route('payment.failed', $id);
        }

        return view('payment.success', compact('payment'));
    }

    // Page d'échec
    public function failed($id){
        $payment = Payment::findOrFail($id);
        return view('payment.failed', compact('payment'));
    }

    // Formatage du numéro de téléphone
    private function formatPhoneNumber($phone)
    {
        // Nettoyer le numéro
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Ajouter l'indicatif +229 si manquant
        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '229')) {
                $phone = '+' . $phone;
            } else {
                $phone = '+229' . ltrim($phone, '0');
            }
        }
        
        return $phone;
    }

    public function callback(Request $request, $token, $status){
        Log::info('🔙 Callback FedaPay reçu', [
            'token' => $token,
            'status' => $status,
            'all_params' => $request->all()
        ]);

        // Trouver le paiement par token
        $payment = Payment::where('payment_token', $token)->first();
        
        if (!$payment) {
            Log::error('Paiement non trouvé pour token', ['token' => $token]);
            return redirect()->route('payment.create')->withErrors([
                'error' => 'Session de paiement invalide'
            ]);
        }

        try {
            // Mettre à jour le statut depuis FedaPay
            $this->initializeFedapay();
            $transaction = Transaction::retrieve($payment->transaction_id);
            
            $oldStatus = $payment->status;
            $newStatus = $transaction->status;
            
            $payment->update([
                'status' => $newStatus,
                'payment_method' => $transaction->mode ?? null
            ]);

            Log::info('Statut mis à jour via callback', [
                'payment_id' => $payment->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'transaction_mode' => $transaction->mode ?? null
            ]);

            // Rediriger vers la page appropriée
            return $this->handlePaymentResult($payment);

        } catch (\Exception $e) {
            Log::error('Erreur lors du callback', [
                'payment_id' => $payment->id ?? null,
                'error' => $e->getMessage()
            ]);
            
            // Rediriger quand même avec le statut connu
            return $this->handlePaymentResult($payment);
        }
    }

    /**
     * Gérer le résultat du paiement
     */
    private function handlePaymentResult($payment){
        switch ($payment->status) {
            case 'approved':
                $payment->markAsPaid($payment->payment_method);
                Log::info('Paiement approuvé', ['payment_id' => $payment->id]);
                return redirect()->route('payment.success', $payment->id)
                    ->with('success', 'Paiement réussi !');
                
            case 'canceled':
                Log::info('Paiement annulé', ['payment_id' => $payment->id]);
                return redirect()->route('payment.failed', $payment->id)
                    ->with('error', 'Paiement annulé');
                
            case 'declined':
                Log::info('Paiement refusé', ['payment_id' => $payment->id]);
                return redirect()->route('payment.failed', $payment->id)
                    ->with('error', 'Paiement refusé (solde insuffisant ou autre raison)');
                
            case 'pending':
            default:
                Log::info('Paiement toujours en attente', ['payment_id' => $payment->id]);
                return redirect()->route('payment.confirm', $payment->id)
                    ->with('info', 'Paiement toujours en cours...');
        }
    }

    /**
     * Actions après succès du paiement
     */
    private function onPaymentSuccess($payment)
    {
        // 1. Envoyer un email de confirmation
        // Mail::to($payment->customer_email)->send(new PaymentConfirmation($payment));
        
        // 2. Mettre à jour votre base de données métier
        // Order::where('payment_id', $payment->id)->update(['status' => 'paid']);
        
        // 3. Notifier l'administrateur
        // Notification::send($admin, new NewPaymentNotification($payment));
        
        // 4. Logger
        Log::info('Paiement traité avec succès', [
            'payment_id' => $payment->id,
            'amount' => $payment->amount,
            'customer' => $payment->customer_email
        ]);
    }

    // ... autres méthodes (confirm, success, failed, checkStatus, etc.) restent



    /**
     * Vérifier le statut d'un paiement
     */
    private function checkPaymentStatus($payment){
        try {
            $this->initializeFedapay();
            $transaction = Transaction::retrieve($payment->transaction_id);
            
            if ($transaction->status !== $payment->status) {
                $payment->update(['status' => $transaction->status]);
                Log::info('Statut synchronisé', [
                    'payment_id' => $payment->id,
                    'new_status' => $transaction->status
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Impossible de synchroniser le statut', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtenir le message d'erreur selon le statut
     */
    private function getErrorMessage($status)
    {
        $messages = [
            'canceled' => 'Vous avez annulé le paiement.',
            'declined' => 'Le paiement a été refusé. Raisons possibles : solde insuffisant, carte bloquée, ou limite dépassée.',
            'pending' => 'Le paiement est toujours en attente de confirmation.',
            'expired' => 'Le lien de paiement a expiré.',
            'error' => 'Une erreur technique est survenue lors du paiement.'
        ];
        
        return $messages[$status] ?? 'Le paiement a échoué pour une raison inconnue.';
    }
    // ... autres méthodes existantes (create, process, confirm, etc.)
    
    /**
     * Test du webhook - GET (pour développement)
     */
    public function webhookTest(Request $request)
    {
        Log::info('Test webhook GET appelé');
        
        // Données de test simulées
        $testData = [
            'name' => 'transaction.approved',
            'data' => [
                'transaction' => [
                    'id' => $request->input('transaction_id', 'test_123'),
                    'status' => $request->input('status', 'approved'),
                    'mode' => $request->input('mode', 'mobile_money'),
                    'amount' => $request->input('amount', 1000),
                    'reference' => $request->input('reference', 'test_ref')
                ]
            ]
        ];
        
        // Si un ID de transaction est fourni, traiter comme un vrai webhook
        $transactionId = $request->input('transaction_id');
        if ($transactionId) {
            $payment = Payment::where('transaction_id', $transactionId)->first();
            
            if ($payment) {
                $oldStatus = $payment->status;
                $newStatus = $request->input('status', 'approved');
                
                $payment->update([
                    'status' => $newStatus,
                    'payment_method' => $request->input('mode', 'mobile_money')
                ]);
                
                if ($newStatus === 'approved') {
                    $payment->markAsPaid($request->input('mode', 'mobile_money'));
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement mis à jour',
                    'payment_id' => $payment->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
            }
        }
        
        // Sinon, afficher une page de test
        $payments = Payment::latest()->take(5)->get();
        
        return view('payment.webhook-test', compact('payments', 'testData'));
    }
    
    public function webhook(Request $request)
    {
        // Log complet de la requête
        Log::info('Webhook FedaPay appelé', [
            'method' => $request->method(),
            'content_type' => $request->header('Content-Type'),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'raw_data' => $request->getContent(),
            'all_data' => $request->all()
        ]);
        
        $payload = $request->all();
        
        if (empty($payload)) {
            $rawContent = $request->getContent();
            if (!empty($rawContent)) {
                $payload = json_decode($rawContent, true) ?? [];
            }
        }
        
        Log::info('Payload webhook analysé', ['payload' => $payload]);
        
        if (isset($payload['id'])) {
            $transactionId = $payload['id'];
            $status = $payload['status'] ?? 'pending';
            
            return $this->handleSimpleWebhook($transactionId, $status, $payload);
        }
        
        if (isset($payload['name']) && isset($payload['data']['transaction'])) {
            $event = $payload['name'];
            $transactionData = $payload['data']['transaction'];
            
            $transactionId = $transactionData['id'];
            $status = $transactionData['status'];
            
            return $this->handleFullWebhook($transactionId, $status, $transactionData, $event);
        }
        
        if (isset($payload['transaction'])) {
            $transactionData = $payload['transaction'];
            $transactionId = $transactionData['id'] ?? null;
            $status = $transactionData['status'] ?? 'pending';
            
            if ($transactionId) {
                return $this->handleSimpleWebhook($transactionId, $status, $transactionData);
            }
        }
        
        Log::error('Format webhook non reconnu', ['payload' => $payload]);
        return response()->json(['error' => 'Format non supporté'], 400);
    }

    private function handleSimpleWebhook($transactionId, $status, $data)
    {
        Log::info('Traitement webhook simple', [
            'transaction_id' => $transactionId,
            'status' => $status,
            'data' => $data
        ]);
        
        $payment = Payment::where('transaction_id', $transactionId)->first();
        
        if (!$payment) {
            Log::error('Paiement non trouvé', ['transaction_id' => $transactionId]);
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $oldStatus = $payment->status;
        $payment->update([
            'status' => $status,
            'payment_method' => $data['mode'] ?? $data['payment_method'] ?? null
        ]);
        
        Log::info('Statut mis à jour (simple)', [
            'payment_id' => $payment->id,
            'old_status' => $oldStatus,
            'new_status' => $status
        ]);
        
        if ($status === 'approved') {
            $payment->markAsPaid($data['mode'] ?? $data['payment_method'] ?? null);
            Log::info('Paiement approuvé via webhook simple', ['payment_id' => $payment->id]);
        }
        
        return response()->json(['status' => 'success']);
    }

    private function handleFullWebhook($transactionId, $status, $transactionData, $event)
    {
        Log::info('Traitement webhook complet', [
            'transaction_id' => $transactionId,
            'status' => $status,
            'event' => $event
        ]);
        
        $payment = Payment::where('transaction_id', $transactionId)->first();
        
        if (!$payment) {
            Log::error('Paiement non trouvé', ['transaction_id' => $transactionId]);
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $oldStatus = $payment->status;
        $payment->update([
            'status' => $status,
            'payment_method' => $transactionData['mode'] ?? null,
            'metadata' => array_merge(
                $payment->metadata ?? [],
                ['webhook_received' => now()->toISOString()]
            )
        ]);
        
        Log::info('Statut mis à jour (complet)', [
            'payment_id' => $payment->id,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'event' => $event
        ]);
        
        if ($status === 'approved') {
            $payment->markAsPaid($transactionData['mode'] ?? null);
            Log::info('Paiement approuvé via webhook complet', ['payment_id' => $payment->id]);
        }
        
        return response()->json(['status' => 'success']);
    }

    public function index(Request $request){
        
        // Récupérer les filtres
        $status = $request->get('status');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $perPage = $request->get('per_page', 20);
        
        // Construire la requête
        $query = Payment::with('user')->latest();
        
        // Appliquer les filtres
        if ($status && $status !== 'all') {
            if ($status === 'successful') {
                $query->successful();
            } elseif ($status === 'pending') {
                $query->pending();
            } elseif ($status === 'failed') {
                $query->failed();
            } elseif ($status === 'refunded') {
                $query->refunded();
            }
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer_email', 'LIKE', "%{$search}%")
                  ->orWhere('customer_phone', 'LIKE', "%{$search}%")
                  ->orWhere('reference', 'LIKE', "%{$search}%")
                  ->orWhere('transaction_id', 'LIKE', "%{$search}%");
            });
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Récupérer les paiements
        $payments = $query->paginate($perPage);
        
        // Statistiques
        $stats = [
            'total' => Payment::count(),
            'successful' => Payment::successful()->count(),
            'pending' => Payment::pending()->count(),
            'failed' => Payment::failed()->count(),
            'today' => Payment::today()->count(),
            'total_amount' => Payment::successful()->sum('amount'),
            'today_amount' => Payment::successful()->today()->sum('amount')
        ];
        
        return view('payment.index', compact('payments', 'stats'));
    }

    public function show($id){
        $payment = Payment::with('user')->findOrFail($id);
        
        // Charger les données FedaPay si nécessaire
        $fedapayData = null;
        try {
            $this->initializeFedapay();
            $transaction = Transaction::retrieve($payment->transaction_id);
            $fedapayData = $transaction;
        } catch (\Exception $e) {
            Log::warning('Impossible de charger les données FedaPay', ['error' => $e->getMessage()]);
        }
        
        return view('payment.show', compact('payment', 'fedapayData'));
    }

    public function userPayments($userId = null){
        $user = $userId ? User::findOrFail($userId) : Auth::user();
        
        // Autorisation
        $this->authorize('viewUserPayments', $user);
        
        $payments = Payment::where('user_id', $user->id)
            ->latest()
            ->paginate(15);
            
        $userStats = [
            'total_payments' => Payment::where('user_id', $user->id)->count(),
            'successful_payments' => Payment::where('user_id', $user->id)->successful()->count(),
            'total_spent' => Payment::where('user_id', $user->id)->successful()->sum('amount'),
            'last_payment' => Payment::where('user_id', $user->id)->latest()->first()
        ];
        
        return view('payment.user', compact('payments', 'user', 'userStats'));
    }

    public function dashboard(){
        
        // Paiements récents
        $recentPayments = Payment::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Statistiques détaillées
        $stats = $this->getPaymentStats();
        
        // Graphique des paiements par jour (7 derniers jours)
        $chartData = $this->getChartData();
        
        return view('payment.dashboard', compact('recentPayments', 'stats', 'chartData'));
    }

    public function export(Request $request)
    {
        $this->authorize('export', Payment::class);
        
        $payments = Payment::query();
        
        // Appliquer les filtres
        if ($request->filled('start_date')) {
            $payments->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $payments->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $payments->where('status', $request->status);
        }
        
        $payments = $payments->get();
        
        $filename = 'paiements_' . date('Y-m-d_H-i') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID',
                'Référence',
                'Transaction ID',
                'Client',
                'Email',
                'Téléphone',
                'Montant',
                'Devise',
                'Statut',
                'Méthode',
                'Date création',
                'Date paiement',
                'Remboursé'
            ]);
            
            // Données
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->reference,
                    $payment->transaction_id,
                    $payment->metadata['firstname'] . ' ' . $payment->metadata['lastname'],
                    $payment->customer_email,
                    $payment->customer_phone,
                    $payment->amount,
                    $payment->currency,
                    $payment->status,
                    $payment->payment_method,
                    $payment->created_at->format('Y-m-d H:i:s'),
                    $payment->paid_at?->format('Y-m-d H:i:s'),
                    $payment->is_refunded ? 'Oui' : 'Non'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }
        
        $payments = Payment::where('customer_email', 'LIKE', "%{$query}%")
            ->orWhere('customer_phone', 'LIKE', "%{$query}%")
            ->orWhere('reference', 'LIKE', "%{$query}%")
            ->orWhere('transaction_id', 'LIKE', "%{$query}%")
            ->take(10)
            ->get()
            ->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'text' => "{$payment->reference} - {$payment->customer_email} - {$payment->formatted_amount}",
                    'reference' => $payment->reference,
                    'email' => $payment->customer_email,
                    'amount' => $payment->formatted_amount,
                    'status' => $payment->status,
                    'url' => route('payments.show', $payment->id)
                ];
            });
        
        return response()->json($payments);
    }

    /**
     * Obtenir les statistiques des paiements
     */
    private function getPaymentStats(): array
    {
        return [
            'daily' => [
                'count' => Payment::today()->count(),
                'amount' => Payment::successful()->today()->sum('amount')
            ],
            'weekly' => [
                'count' => Payment::thisWeek()->count(),
                'amount' => Payment::successful()->thisWeek()->sum('amount')
            ],
            'monthly' => [
                'count' => Payment::thisMonth()->count(),
                'amount' => Payment::successful()->thisMonth()->sum('amount')
            ],
            'methods' => Payment::successful()
                ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('payment_method')
                ->get()
                ->mapWithKeys(function($item) {
                    return [$item->payment_method => [
                        'count' => $item->count,
                        'total' => $item->total
                    ]];
                })->toArray()
        ];
    }

    /**
     * Données pour le graphique
     */
    private function getChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Payment::whereDate('created_at', $date)->count();
            $amount = Payment::whereDate('created_at', $date)->successful()->sum('amount');
            
            $data['labels'][] = now()->subDays($i)->format('d/m');
            $data['counts'][] = $count;
            $data['amounts'][] = (int) $amount;
        }
        
        return $data;
    }
    

}