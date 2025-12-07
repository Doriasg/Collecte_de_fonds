<?php
// app/Services/FedaPayService.php

namespace App\Services;

use FedaPay\FedaPay;
use FedaPay\Transaction;
use FedaPay\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FedaPayService
{
    private $apiKey;
    private $environment;
    private $currency;

    public function __construct()
    {
        $this->apiKey = config('fedapay.api_key');
        $this->environment = config('fedapay.environment');
        $this->currency = config('fedapay.currency');
        
        $this->initialize();
    }

    private function initialize()
    {
        try {
            if (empty($this->apiKey)) {
                throw new \Exception('Clé API FedaPay non configurée');
            }
            
            FedaPay::setApiKey($this->apiKey);
            FedaPay::setEnvironment($this->environment);
            
            Log::info('FedaPay service initialisé', [
                'environment' => $this->environment,
                'currency' => $this->currency
            ]);
            
        } catch (\Exception $e) {
            Log::error('Échec initialisation FedaPay: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Créer un client FedaPay
     */
    public function createCustomer(array $data)
    {
        try {
            Log::info('Création client FedaPay', [
                'email' => $data['email'],
                'phone' => $data['phone']
            ]);
            
            $customer = Customer::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'phone_number' => [
                    'number' => $data['phone'],
                    'country' => $data['country'] ?? 'bj'
                ]
            ]);
            
            Log::info('Client créé avec succès', [
                'customer_id' => $customer->id,
                'email' => $customer->email
            ]);
            
            return [
                'success' => true,
                'customer' => $customer,
                'id' => $customer->id,
                'data' => $customer->toArray()
            ];
            
        } catch (\FedaPay\Error\InvalidRequest $e) {
            Log::error('Requête invalide pour création client', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            throw new \Exception('Erreur création client: ' . $this->parseCustomerError($e->getMessage()));
            
        } catch (\Exception $e) {
            Log::error('Erreur création client FedaPay', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Créer une transaction
     */
    public function createTransaction(array $data)
    {
        try {
            $required = ['amount', 'description', 'customer_id'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    throw new \Exception("Le champ $field est requis");
                }
            }
            
            $transactionData = [
                'description' => $data['description'],
                'amount' => (int) $data['amount'],
                'currency' => ['iso' => $this->currency['iso']],
                'callback_url' => $data['callback_url'] ?? route('payment.callback'),
                'mode' => $data['mode'] ?? 'automatic',
                'customer' => $data['customer_id'],
                'custom_metadata' => $data['metadata'] ?? []
            ];
            
            Log::info('Création transaction FedaPay', $transactionData);
            
            $transaction = Transaction::create($transactionData);
            
            Log::info('Transaction créée avec succès', [
                'transaction_id' => $transaction->id,
                'reference' => $transaction->reference,
                'amount' => $transaction->amount
            ]);
            
            return [
                'success' => true,
                'transaction' => $transaction,
                'id' => $transaction->id,
                'reference' => $transaction->reference,
                'status' => $transaction->status,
                'data' => $transaction->toArray()
            ];
            
        } catch (\FedaPay\Error\InvalidRequest $e) {
            Log::error('Requête invalide pour création transaction', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            
            throw new \Exception('Erreur création transaction: ' . $e->getMessage());
            
        } catch (\Exception $e) {
            Log::error('Erreur création transaction FedaPay', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    /**
     * Générer un token de paiement
     */
    public function generatePaymentToken($transactionId)
    {
        try {
            Log::info('Génération token pour transaction', ['transaction_id' => $transactionId]);
            
            $transaction = Transaction::retrieve($transactionId);
            
            if (!$transaction) {
                throw new \Exception('Transaction non trouvée');
            }
            
            $token = $transaction->generateToken();
            
            if (empty($token->url)) {
                throw new \Exception('URL de paiement non générée');
            }
            
            Log::info('Token généré avec succès', [
                'transaction_id' => $transactionId,
                'url' => $token->url
            ]);
            
            return [
                'success' => true,
                'token' => $token,
                'url' => $token->url,
                'transaction' => $transaction
            ];
            
        } catch (\Exception $e) {
            Log::error('Erreur génération token', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);
            
            throw $e;
        }
    }

    /**
     * Récupérer une transaction
     */
    public function getTransaction($transactionId)
    {
        try {
            $transaction = Transaction::retrieve($transactionId);
            
            return [
                'success' => true,
                'transaction' => $transaction,
                'data' => $this->formatTransaction($transaction)
            ];
            
        } catch (\Exception $e) {
            Log::error('Erreur récupération transaction', [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);
            
            throw $e;
        }
    }

    /**
     * Vérifier le statut d'une transaction
     */
    public function checkTransactionStatus($transactionId)
    {
        try {
            $transaction = $this->getTransaction($transactionId);
            
            $status = $transaction['data']['status'] ?? 'unknown';
            
            $statusInfo = [
                'status' => $status,
                'is_successful' => in_array($status, ['approved', 'transferred']),
                'is_pending' => in_array($status, ['pending', 'created']),
                'is_failed' => in_array($status, ['canceled', 'declined', 'refunded', 'error']),
                'message' => $this->getStatusMessage($status)
            ];
            
            return array_merge($transaction, ['status_info' => $statusInfo]);
            
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Valider la signature webhook
     */
    public function validateWebhook($payload, $signature)
    {
        $secret = config('fedapay.webhook_secret');
        
        if (!$secret) {
            Log::warning('Secret webhook non configuré');
            return false;
        }
        
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Calculer les frais
     */
    public function calculateFees($amount)
    {
        $percentage = config('fedapay.fees.percentage', 1.5);
        $minFee = config('fedapay.fees.min_amount', 100);
        $maxFee = config('fedapay.fees.max_amount', 2500);
        
        $fees = ($amount * $percentage) / 100;
        $fees = max($fees, $minFee);
        $fees = min($fees, $maxFee);
        
        return round($fees);
    }

    /**
     * Formater la transaction
     */
    private function formatTransaction($transaction)
    {
        return [
            'id' => $transaction->id,
            'reference' => $transaction->reference,
            'amount' => $transaction->amount,
            'description' => $transaction->description,
            'callback_url' => $transaction->callback_url,
            'status' => $transaction->status,
            'customer_id' => $transaction->customer_id,
            'currency' => $transaction->currency,
            'mode' => $transaction->mode,
            'metadata' => $transaction->metadata,
            'created_at' => $transaction->created_at,
            'updated_at' => $transaction->updated_at,
            'approved_at' => $transaction->approved_at,
            'canceled_at' => $transaction->canceled_at,
            'declined_at' => $transaction->declined_at,
            'payment_url' => $transaction->generateToken()->url ?? null
        ];
    }

    /**
     * Obtenir le message du statut
     */
    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'Paiement en attente',
            'created' => 'Transaction créée',
            'approved' => 'Paiement approuvé',
            'transferred' => 'Fonds transférés',
            'canceled' => 'Paiement annulé',
            'declined' => 'Paiement refusé',
            'refunded' => 'Paiement remboursé',
            'error' => 'Erreur de paiement'
        ];
        
        return $messages[$status] ?? 'Statut inconnu';
    }

    /**
     * Parser les erreurs de client
     */
    private function parseCustomerError($error)
    {
        if (strpos($error, 'email has already been taken') !== false) {
            return 'Cet email est déjà utilisé';
        }
        
        if (strpos($error, 'phone_number has already been taken') !== false) {
            return 'Ce numéro de téléphone est déjà utilisé';
        }
        
        if (strpos($error, 'Invalid email') !== false) {
            return 'Email invalide';
        }
        
        if (strpos($error, 'Invalid phone number') !== false) {
            return 'Numéro de téléphone invalide';
        }
        
        return $error;
    }
}