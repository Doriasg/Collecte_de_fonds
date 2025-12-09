<?php
// test-fedapay.php à la racine
require __DIR__.'/vendor/autoload.php';

use FedaPay\FedaPay;
use FedaPay\Customer;

// Charger l'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "=== Test FedaPay Customer Creation ===\n";
echo "Mode: " . $_ENV['FEDAPAY_MODE'] . "\n";

try {
    FedaPay::setApiKey($_ENV['FEDAPAY_SECRET_KEY']);
    FedaPay::setEnvironment($_ENV['FEDAPAY_MODE']);
    
    // Tester la création d'un client simple
    $customer = Customer::create([
        'firstname' => 'Test',
        'lastname' => 'User',
        'email' => 'test@example.com',
        'phone_number' => [
            'number' => '+22901020304',
            'country' => 'bj'
        ]
    ]);
    
    echo "Client créé avec succès!\n";
    echo "Client ID: " . $customer->id . "\n";
    echo "Email: " . $customer->email . "\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Type: " . get_class($e) . "\n";
}