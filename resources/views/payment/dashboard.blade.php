<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord des Paiements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-800">
                    <i class="fas fa-chart-line mr-2"></i>Tableau de bord
                </h1>
                <div class="space-x-3">
                    <a href="{{ route('admin.payments.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fas fa-list mr-1"></i> Liste des paiements
                    </a>
                </div>
            </div>
            <p class="text-gray-600 mt-2">Statistiques et analyses des paiements</p>
        </div>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total aujourd'hui -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-calendar-day text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Aujourd'hui</p>
                        <p class="text-2xl font-bold">{{ $stats['daily']['count'] }} paiements</p>
                        <p class="text-lg text-gray-700">{{ number_format($stats['daily']['amount'], 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>
            
            <!-- Cette semaine -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-calendar-week text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Cette semaine</p>
                        <p class="text-2xl font-bold">{{ $stats['weekly']['count'] }} paiements</p>
                        <p class="text-lg text-gray-700">{{ number_format($stats['weekly']['amount'], 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>
            
            <!-- Ce mois -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg mr-4">
                        <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Ce mois</p>
                        <p class="text-2xl font-bold">{{ $stats['monthly']['count'] }} paiements</p>
                        <p class="text-lg text-gray-700">{{ number_format($stats['monthly']['amount'], 0, ',', ' ') }} FCFA</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Graphique des paiements par jour -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Paiements des 7 derniers jours</h2>
                <div class="h-64">
                    <canvas id="paymentsChart"></canvas>
                </div>
            </div>
            
            <!-- Méthodes de paiement -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Répartition par méthode</h2>
                <div class="space-y-4">
                    @foreach($stats['methods'] as $method => $data)
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-gray-700">
                                    @switch($method)
                                        @case('mobile_money') Mobile Money @break
                                        @case('credit_card') Carte bancaire @break
                                        @case('bank_transfer') Virement @break
                                        @default {{ ucfirst($method) }}
                                    @endswitch
                                </span>
                                <span class="font-medium">{{ $data['count'] }} paiements</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $totalMethods = array_sum(array_column($stats['methods'], 'count'));
                                    $width = $totalMethods > 0 ? round(($data['count'] / $totalMethods) * 100) : 0;
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $width }}%"></div>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                {{ number_format($data['total'], 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Derniers paiements -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Derniers paiements</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($recentPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ $payment->customer_email }}</div>
                                    <div class="text-sm text-gray-500">{{ $payment->reference }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $payment->status_badge['class'] }}">
                                        {{ $payment->status_badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $payment->formatted_created_at }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t">
                <a href="{{ route('admin.payments.index') }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-right mr-1"></i> Voir tous les paiements
                </a>
            </div>
        </div>
    </div>

    <script>
        // Graphique des paiements
        const ctx = document.getElementById('paymentsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Nombre de paiements',
                        data: @json($chartData['counts']),
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Montant (FCFA)',
                        data: @json($chartData['amounts']),
                        backgroundColor: 'rgba(34, 197, 94, 0.5)',
                        borderColor: 'rgb(34, 197, 94)',
                        borderWidth: 1,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre de paiements'
                        }
                    },
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Montant (FCFA)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>