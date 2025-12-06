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
    public function __construct()
    {
        $this->initializeFedaPay();
    }

    private function initializeFedaPay()
    {
        try {
            $secretKey = env('FEDAPAY_SECRET_KEY');
            $mode = env('FEDAPAY_MODE', 'sandbox');
            
            if (empty($secretKey)) {
                throw new \Exception('Clé secrète FedaPay non configurée');
            }
            
            FedaPay::setApiKey($secretKey);
            FedaPay::setEnvironment($mode);
            
            // Pour le mode sandbox
            if ($mode === 'sandbox') {
                FedaPay::setCustomBaseUrl('https://sandbox-api.fedapay.com');
            }
            
            Log::info('FedaPay initialisé', ['mode' => $mode]);
            
        } catch (\Exception $e) {
            Log::error('Erreur initialisation FedaPay: ' . $e->getMessage());
        }
    }

    public function soutenir()
    {
        return view('form_soutient');
    }

    public function processPayment(Request $request)
    {
        // Valider les données
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:100|max:1000000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $validator->validated();
            
            // Log pour debug
            Log::info('Données reçues', $data);
            
            // Nettoyer et valider le téléphone
            $phone = $this->cleanPhoneNumber($data['phone']);
            
            
            // Préparer les noms
            $names = $this->prepareNames($data['full_name']);
            
            // Créer le client
            Log::info('Création client FedaPay', [
                'email' => $data['email'],
                'phone' => $phone
            ]);
            
            $customer = Customer::create([
                'firstname' => $names['firstname'],
                'lastname' => $names['lastname'],
                'email' => $data['email'],
                'phone_number' => [
                    'number' => $phone,
                    'country' => 'bj'
                ]
            ]);
            
            Log::info('Client créé', ['customer_id' => $customer->id]);
            
            // Créer la transaction
            $transaction = Transaction::create([
                'description' => 'Donation - ' . $data['full_name'],
                'amount' => intval($data['amount']),
                'currency' => ['iso' => 'XOF'],
                'callback_url' => route('payment.callback'),
                'customer' => $customer->id,
                'include' => ['customer']
            ]);
            
            Log::info('Transaction créée', [
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference
            ]);
            
            // Générer le token
            $token = $transaction->generateToken();
            
            if (empty($token->url)) {
                throw new \Exception('URL de paiement non générée');
            }
            
            // Rediriger vers FedaPay
            return redirect($token->url);
            
        } catch (\FedaPay\Error\ApiConnection $e) {
            Log::error('Erreur connexion API FedaPay: ' . $e->getMessage());
            
            // Message plus détaillé
            $errorMessage = $this->parseFedaPayError($e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur FedaPay: ' . $errorMessage);
                
        } catch (\FedaPay\Error\InvalidRequest $e) {
            Log::error('Requête invalide FedaPay: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Données invalides pour FedaPay: ' . $e->getMessage());
                
        } catch (\Exception $e) {
            Log::error('Erreur générale: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    private function cleanPhoneNumber($phone)
    {
        // Supprimer tous les caractères non numériques sauf +
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Si le numéro commence par 0 et a 9 chiffres
        if (strlen($phone) === 9 && $phone[0] === '0') {
            return '+229' . substr($phone, 1);
        }
        
        // Si le numéro a 8 chiffres (sans le 0 initial)
        if (strlen($phone) === 8 && is_numeric($phone)) {
            return '+229' . $phone;
        }
        
        // Si commence par 229 (sans +)
        if (strlen($phone) === 11 && strpos($phone, '229') === 0) {
            return '+' . $phone;
        }
        
        // Si commence par 00229
        if (strlen($phone) === 13 && strpos($phone, '00229') === 0) {
            return '+229' . substr($phone, 5);
        }
        
        // Retourner tel quel
        return $phone;
    }

    private function isValidBeninPhone($phone)
    {
        // Format attendu: +229XXXXXXXXX (où X est un chiffre)
        return preg_match('/^\+229[0-9]{8}$/', $phone) === 1;
    }

    private function prepareNames($fullName)
    {
        $parts = explode(' ', trim($fullName), 2);
        
        return [
            'firstname' => $parts[0] ?? 'Client',
            'lastname' => $parts[1] ?? '.'
        ];
    }

    private function parseFedaPayError($errorMessage)
    {
        // Messages d'erreur courants FedaPay
        $errors = [
            'la création du client a échoué' => 'Échec de création du client. Vérifiez les données (email et téléphone).',
            'Invalid email' => 'Email invalide.',
            'Invalid phone number' => 'Numéro de téléphone invalide.',
            'phone_number is required' => 'Le numéro de téléphone est requis.',
            'email is required' => 'L\'email est requis.',
        ];
        
        foreach ($errors as $key => $message) {
            if (stripos($errorMessage, $key) !== false) {
                return $message;
            }
        }
        
        return $errorMessage;
    }

    public function paymentCallback(Request $request)
    {
        try {
            $transactionId = $request->input('id') ?? $request->input('transaction_id');
            
            if (!$transactionId) {
                Log::warning('Callback sans ID', $request->all());
                return view('payment.failed', [
                    'message' => 'Transaction non trouvée'
                ]);
            }
            
            // Récupérer la transaction
            $transaction = Transaction::retrieve($transactionId, [
                'include' => ['customer']
            ]);
            
            Log::info('Callback statut', [
                'id' => $transaction->id,
                'status' => $transaction->status
            ]);
            
            if ($transaction->status === 'approved') {
                return view('payment.success', [
                    'transaction' => $transaction,
                    'customer' => $transaction->customer
                ]);
            } else {
                return view('payment.failed', [
                    'message' => 'Paiement non approuvé. Statut: ' . $transaction->status
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Erreur callback: ' . $e->getMessage());
            return view('payment.failed', [
                'message' => 'Erreur lors de la vérification'
            ]);
        }
    }

    // Route pour tester directement
    public function testFedaPay()
    {
        try {
            // Test simple de création de client
            $customer = Customer::create([
                'firstname' => 'Test',
                'lastname' => 'API',
                'email' => 'testapi@example.com',
                'phone_number' => [
                    'number' => '+22994119476',
                    'country' => 'bj'
                ]
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'FedaPay fonctionne!',
                'customer_id' => $customer->id
            ]);
            
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'config' => [
                    'has_key' => !empty(env('FEDAPAY_SECRET_KEY')),
                    'mode' => env('FEDAPAY_MODE'),
                    'key_prefix' => substr(env('FEDAPAY_SECRET_KEY'), 0, 20) . '...'
                ]
            ], 500);
        }
    }
}