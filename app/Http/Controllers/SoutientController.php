<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use FedaPay\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SoutientController extends Controller
{
    private $fedapayMode;
    private $isLiveMode = false;

    public function __construct()
    {
        $this->fedapayMode = env('FEDAPAY_MODE', 'sandbox');
        $this->isLiveMode = $this->fedapayMode === 'live';
        
        $this->initializeFedaPay();
    }

    private function initializeFedaPay()
    {
        try {
            $secretKey = env('FEDAPAY_SECRET_KEY');
            
            if (empty($secretKey)) {
                throw new \Exception('Clé FedaPay non configurée dans .env');
            }
            
            // Vérifier si c'est une clé sandbox ou live
            $keyType = strpos($secretKey, 'sk_sandbox_') === 0 ? 'sandbox' : 
                      (strpos($secretKey, 'sk_live_') === 0 ? 'live' : 'inconnu');
            
            if ($keyType !== $this->fedapayMode) {
                Log::warning('Incohérence clé/mode', [
                    'key_type' => $keyType,
                    'config_mode' => $this->fedapayMode
                ]);
            }
            
            // Configuration FedaPay selon la documentation officielle
            FedaPay::setApiKey($secretKey);
            FedaPay::setEnvironment($this->fedapayMode);
            
            Log::info('FedaPay initialisé', [
                'mode' => $this->fedapayMode,
                'key_type' => $keyType,
                'is_live' => $this->isLiveMode
            ]);
            
        } catch (\Exception $e) {
            Log::critical('Échec initialisation FedaPay: ' . $e->getMessage());
        }
    }

    public function soutenir()
    {
        return view('form_soutient', [
            'isLiveMode' => $this->isLiveMode,
            'fedapayMode' => $this->fedapayMode
        ]);
    }

    public function processPayment(Request $request)
    {
        // Valider les données de base
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:100|max:500000',
        ], [
            'phone.max' => 'Le numéro ne doit pas dépasser 20 caractères',
            'amount.min' => 'Le montant minimum est de 100 FCFA',
            'amount.max' => 'Le montant maximum est de 500,000 FCFA',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs ci-dessous');
        }

        $data = $validator->validated();
        
        // Log initial
        Log::info('Début traitement paiement', [
            'email' => $data['email'],
            'mode' => $this->fedapayMode
        ]);

        try {
            // 1. Préparer les données
            $phone = $this->formatPhoneForFedaPay($data['phone']);
            $names = $this->splitName($data['full_name']);
            
            // Pour le sandbox, utiliser un email unique
            $email = $data['email'];
            if ($this->fedapayMode === 'sandbox') {
                $email = 'test_' . time() . '_' . $data['email'];
            }
            
            // 2. Créer le client
            Log::info('Création client', [
                'email' => $email,
                'phone' => $phone,
                'firstname' => $names['firstname'],
                'lastname' => $names['lastname']
            ]);
            
            $customer = Customer::create([
                'firstname' => $names['firstname'],
                'lastname' => $names['lastname'],
                'email' => $email,
                'phone_number' => [
                    'number' => $phone,
                    'country' => 'bj'
                ]
            ]);
            
            Log::info('✅ Client créé', ['customer_id' => $customer->id]);
            
            // 3. Créer la transaction
            $transaction = Transaction::create([
                'description' => 'Donation - ' . substr($data['full_name'], 0, 50),
                'amount' => (int) $data['amount'],
                'currency' => ['iso' => 'XOF'],
                'callback_url' => route('payment.callback'),
                'customer' => $customer->id
            ]);
            
            Log::info('✅ Transaction créée', [
                'id' => $transaction->id,
                'reference' => $transaction->reference
            ]);
            
            // 4. Générer le token
            $token = $transaction->generateToken();
            
            if (empty($token->url)) {
                throw new \Exception('URL de paiement non générée');
            }
            
            Log::info('🎯 Redirection vers FedaPay', [
                'url' => $token->url,
                'transaction_id' => $transaction->id
            ]);
            
            return redirect($token->url);
            
        } catch (\FedaPay\Error\ApiConnection $e) {
            Log::error('❌ Erreur API FedaPay', [
                'message' => $e->getMessage(),
                'mode' => $this->fedapayMode
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur FedaPay: ' . $this->parseApiError($e->getMessage()));
                
        } catch (\FedaPay\Error\InvalidRequest $e) {
            Log::error('❌ Requête invalide FedaPay', [
                'message' => $e->getMessage()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Données invalides: ' . $e->getMessage());
                
        } catch (\Exception $e) {
            Log::error('❌ Erreur générale', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    private function formatPhoneForFedaPay($phone)
    {
        // Nettoyer
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Convertir en format FedaPay standard
        if (preg_match('/^0(\d{8})$/', $phone, $matches)) {
            return '+229' . $matches[1]; // 0XXXXXXXX → +229XXXXXXXX
        }
        
        if (preg_match('/^(\d{8})$/', $phone, $matches)) {
            return '+229' . $matches[1]; // XXXXXXXX → +229XXXXXXXX
        }
        
        if (preg_match('/^229(\d{8})$/', $phone, $matches)) {
            return '+229' . $matches[1]; // 229XXXXXXXX → +229XXXXXXXX
        }
        
        if (preg_match('/^\+229(\d{8})$/', $phone, $matches)) {
            return $phone; // Déjà bon format
        }
        
        throw new \Exception(
            'Format téléphone invalide. ' .
            'Fourni: ' . $phone . '. ' .
            'Attendu: +229XXXXXXXX, 0XXXXXXXX, ou XXXXXXXX'
        );
    }

    private function splitName($fullName)
    {
        $fullName = trim($fullName);
        $parts = explode(' ', $fullName, 2);
        
        return [
            'firstname' => $parts[0] ?? 'Donateur',
            'lastname' => $parts[1] ?? 'Anonyme'
        ];
    }

    private function parseApiError($errorMessage)
    {
        if (strpos($errorMessage, 'HTTP response code was 500') !== false) {
            return 'Erreur serveur FedaPay. ' . 
                   ($this->isLiveMode ? 
                    'Vérifiez votre compte en mode production.' : 
                    'Réessayez en mode sandbox.');
        }
        
        if (strpos($errorMessage, 'json_last_error() was 4') !== false) {
            return 'Réponse invalide de FedaPay.';
        }
        
        if (strpos($errorMessage, 'la création du client a échoué') !== false) {
            return 'Échec création client. Vérifiez email/téléphone.';
        }
        
        return substr($errorMessage, 0, 100);
    }

    // Route de test simple
    public function testFedaPay()
    {
        try {
            // Test simple de connexion
            $account = \FedaPay\Account::all(['per_page' => 1]);
            
            return response()->json([
                'success' => true,
                'message' => '✅ Connexion FedaPay OK',
                'mode' => $this->fedapayMode,
                'account' => [
                    'id' => $account->first()->id ?? 'N/A',
                    'name' => $account->first()->name ?? 'N/A'
                ]
            ]);
            
        } 
        catch (\FedaPay\Error\Authentication $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Erreur d\'authentification',
                'error' => $e->getMessage(),
                'config' => [
                    'mode' => $this->fedapayMode,
                    'has_key' => !empty(env('FEDAPAY_SECRET_KEY')),
                    'key_prefix' => env('FEDAPAY_SECRET_KEY') ? substr(env('FEDAPAY_SECRET_KEY'), 0, 15) . '...' : 'N/A'
                ]
            ], 401);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Erreur FedaPay',
                'error' => $e->getMessage(),
                'mode' => $this->fedapayMode
            ], 500);
        }
    }

    public function paymentCallback(Request $request)
    {
        try {
            $transactionId = $request->input('id') ?? $request->input('transaction_id');
            
            if (!$transactionId) {
                return view('payment.failed', [
                    'message' => 'Transaction introuvable'
                ]);
            }
            
            $transaction = Transaction::retrieve($transactionId);
            
            if ($transaction->status === 'approved') {
                return view('payment.success', [
                    'transaction' => $transaction,
                    'message' => 'Merci pour votre don!'
                ]);
            } else {
                return view('payment.failed', [
                    'message' => 'Paiement ' . $transaction->status
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur callback', ['error' => $e->getMessage()]);
            
            return view('payment.failed', [
                'message' => 'Erreur lors du traitement'
            ]);
        }
    }
}