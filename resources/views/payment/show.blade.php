<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Paiement #{{ $payment->reference }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.payments.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <h1 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-receipt mr-2"></i>Paiement #{{ $payment->reference }}
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Carte de statut -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Statut du paiement</h2>
                            <p class="text-sm text-gray-500">Transaction ID: {{ $payment->transaction_id }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $payment->status_badge['class'] }}">
                            {{ $payment->status_badge['label'] }}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Montant</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Méthode de paiement</p>
                            <p class="text-lg font-medium">
                                <span class="mr-2">{{ $payment->payment_method_icon }}</span>
                                {{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : 'Non spécifié' }}
                            </p>
                        </div>
                    </div>
                    
                    @if($payment->isSuccessful())
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded">
                            <p class="text-green-700">
                                <i class="fas fa-check-circle mr-2"></i>
                                Paiement effectué le {{ $payment->formatted_paid_at }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Informations client -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-user mr-2"></i>Informations client
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nom complet</p>
                            <p class="font-medium">
                                {{ $payment->metadata['firstname'] ?? 'N/A' }} 
                                {{ $payment->metadata['lastname'] ?? '' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $payment->customer_email }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="font-medium">{{ $payment->customer_phone }}</p>
                        </div>
                        
                        @if($payment->user)
                            <div>
                                <p class="text-sm text-gray-500">Compte utilisateur</p>
                                <a href="{{ route('admin.payments.user', $payment->user_id) }}" 
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $payment->user->name }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Détails de la transaction -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-info-circle mr-2"></i>Détails de la transaction
                    </h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Date de création</span>
                            <span class="font-medium">{{ $payment->formatted_created_at }}</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b">
                            <span class="text-gray-600">Référence interne</span>
                            <span class="font-mono font-medium">{{ $payment->reference }}</span>
                        </div>
                        
                        @if($payment->transaction_reference)
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Référence transaction</span>
                                <span class="font-mono">{{ $payment->transaction_reference }}</span>
                            </div>
                        @endif
                        
                        @if($payment->description)
                            <div class="py-2 border-b">
                                <p class="text-gray-600 mb-1">Description</p>
                                <p class="font-medium">{{ $payment->metadata['description'] ?? $payment->description }}</p>
                            </div>
                        @endif
                        
                        @if($payment->notes)
                            <div class="py-2">
                                <p class="text-gray-600 mb-1">Notes</p>
                                <p class="font-medium">{{ $payment->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions et métadonnées -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Actions</h2>
                    
                    <div class="space-y-3">
                        @if($payment->payment_url && $payment->isPending())
                            <a href="{{ $payment->payment_url }}" target="_blank"
                               class="w-full bg-green-600 text-white py-2 px-4 rounded flex items-center justify-center hover:bg-green-700">
                                <i class="fas fa-external-link-alt mr-2"></i> Accéder au lien de paiement
                            </a>
                        @endif
                        
                        @if($payment->isSuccessful() && !$payment->is_refunded)
                            <button onclick="confirmRefund()"
                               class="w-full bg-purple-600 text-white py-2 px-4 rounded flex items-center justify-center hover:bg-purple-700">
                                <i class="fas fa-undo mr-2"></i> Rembourser le paiement
                            </button>
                        @endif
                        
                        <a href="javascript:window.print()"
                           class="w-full bg-gray-600 text-white py-2 px-4 rounded flex items-center justify-center hover:bg-gray-700">
                            <i class="fas fa-print mr-2"></i> Imprimer le reçu
                        </a>
                        
                        <button onclick="copyToClipboard('{{ $payment->reference }}')"
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded flex items-center justify-center hover:bg-blue-700">
                            <i class="fas fa-copy mr-2"></i> Copier la référence
                        </button>
                    </div>
                </div>

                <!-- Métadonnées FedaPay -->
                @if($fedapayData)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-database mr-2"></i>Données FedaPay
                        </h2>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID FedaPay:</span>
                                <span class="font-mono">{{ $fedapayData->id }}</span>
                            </div>
                            
                            @if(isset($fedapayData->mode))
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Mode:</span>
                                    <span>{{ $fedapayData->mode }}</span>
                                </div>
                            @endif
                            
                            @if(isset($fedapayData->operation))
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Opération:</span>
                                    <span>{{ $fedapayData->operation }}</span>
                                </div>
                            @endif
                            
                            <button onclick="showRawData()" 
                                    class="w-full mt-3 text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-code mr-1"></i> Voir les données brutes
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Notes rapides -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ajouter une note</h2>
                    
                    <form id="noteForm">
                        @csrf
                        <textarea name="notes" rows="3" 
                                  class="w-full border border-gray-300 rounded p-2 mb-3"
                                  placeholder="Ajoutez une note interne...">{{ $payment->notes }}</textarea>
                        <button type="submit" 
                                class="w-full bg-yellow-600 text-white py-2 px-4 rounded hover:bg-yellow-700">
                            <i class="fas fa-save mr-2"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function confirmRefund() {
            if (confirm('Êtes-vous sûr de vouloir rembourser ce paiement ? Cette action est irréversible.')) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Paiement remboursé avec succès');
                        location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                });
            }
        }
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Référence copiée: ' + text);
            });
        }
        
        function showRawData() {
            const data = @json($fedapayData ?? null);
            if (data) {
                alert(JSON.stringify(data, null, 2));
            }
        }
        
        // Gestion des notes
        document.getElementById('noteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    notes: formData.get('notes')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Note enregistrée');
                }
            });
        });
    </script>
</body>
</html>