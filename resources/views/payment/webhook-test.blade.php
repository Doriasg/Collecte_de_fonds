<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tester Webhook FedaPay</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Test Webhook FedaPay</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Liste des paiements récents -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Paiements récents</h2>
                
                @if($payments->isEmpty())
                    <p class="text-gray-500">Aucun paiement trouvé</p>
                @else
                    <div class="space-y-3">
                        @foreach($payments as $payment)
                            <div class="border rounded p-3">
                                <div class="flex justify-between">
                                    <span class="font-medium">ID: {{ $payment->transaction_id }}</span>
                                    <span class="px-2 py-1 rounded text-xs font-bold
                                        {{ $payment->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           'bg-red-100 text-red-800') }}">
                                        {{ strtoupper($payment->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600 mt-2">
                                    <div>Montant: {{ number_format($payment->amount, 0, ',', ' ') }} FCFA</div>
                                    <div>Référence: {{ $payment->reference }}</div>
                                    <div class="mt-2">
                                        <a href="{{ route('payment.webhook-test') }}?transaction_id={{ $payment->transaction_id }}&status=approved" 
                                           class="inline-block bg-green-500 text-white px-3 py-1 rounded text-sm mr-2">
                                           Simuler succès
                                        </a>
                                        <a href="{{ route('payment.webhook-test') }}?transaction_id={{ $payment->transaction_id }}&status=canceled" 
                                           class="inline-block bg-red-500 text-white px-3 py-1 rounded text-sm">
                                           Simuler échec
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Informations sur le webhook -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Informations Webhook</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium mb-2">URL du webhook :</h3>
                        <div class="bg-gray-50 p-3 rounded font-mono text-sm">
                            {{ route('payment.webhook') }}
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            Méthode: <strong>POST</strong><br>
                            Headers: Content-Type: application/json
                        </p>
                    </div>
                    
                    <div>
                        <h3 class="font-medium mb-2">Format des données :</h3>
                        <pre class="bg-gray-50 p-3 rounded text-sm overflow-auto">{{ json_encode($testData, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    
                    <div>
                        <h3 class="font-medium mb-2">Pour tester avec curl :</h3>
                        <div class="bg-gray-900 text-gray-100 p-3 rounded font-mono text-sm">
                            curl -X POST {{ route('payment.webhook') }} \<br>
                            &nbsp;&nbsp;-H "Content-Type: application/json" \<br>
                            &nbsp;&nbsp;-d '{{ json_encode($testData) }}'
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded p-4">
                        <h4 class="font-medium text-blue-800 mb-2">⚠️ Important</h4>
                        <p class="text-sm text-blue-700">
                            FedaPay envoie les notifications via POST. Cette page GET est uniquement pour le développement.
                            En production, seule l'URL POST sera utilisée.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>