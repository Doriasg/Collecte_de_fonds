<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Paiements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">
                        <i class="fas fa-credit-card mr-2"></i>Gestion des Paiements
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('payment.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-plus mr-1"></i> Nouveau paiement
                    </a>
                    <a href="{{ route('admin.payments.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-chart-bar mr-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Statistiques -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-receipt text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Total paiements</p>
                        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Réussis</p>
                        <p class="text-2xl font-bold">{{ $stats['successful'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">En attente</p>
                        <p class="text-2xl font-bold">{{ $stats['pending'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-500">Échecs</p>
                        <p class="text-2xl font-bold">{{ $stats['failed'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Recherche -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white rounded-lg shadow p-4">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                        <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Réussis</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échecs</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Remboursés</option>
                    </select>
                </div>
                
                <!-- Date de début -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Du</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                
                <!-- Date de fin -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Au</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full border border-gray-300 rounded px-3 py-2">
                </div>
                
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Recherche</label>
                    <div class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Email, téléphone, référence..."
                               class="w-full border border-gray-300 rounded-l px-3 py-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des paiements -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Référence
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Client
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Montant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Méthode
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $payment->reference }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ substr($payment->transaction_id, 0, 15) }}...
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $payment->customer_email }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $payment->customer_phone }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payment->status_badge['class'] }}">
                                        {{ $payment->status_badge['label'] }}
                                    </span>
                                    @if($payment->is_refunded)
                                        <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Remboursé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <span class="mr-2">{{ $payment->payment_method_icon }}</span>
                                        {{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : 'Non spécifié' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $payment->formatted_created_at }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i> Détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Aucun paiement trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function confirmRefund(paymentId) {
            if (confirm('Voulez-vous vraiment rembourser ce paiement ?')) {
                fetch(`/admin/payments/${paymentId}/refund`, {
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
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue');
                });
            }
        }
        
        // Recherche en temps réel
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;
        
        searchInput?.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (e.target.value.length >= 2) {
                    fetch(`{{ route('admin.payments.search') }}?q=${encodeURIComponent(e.target.value)}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Suggestions:', data);
                            // Implémentez l'autocomplétion si nécessaire
                        });
                }
            }, 300);
        });
    </script>
</body>
</html>