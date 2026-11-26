<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord – Mission Sourire Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-2px); transition: transform 0.2s; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

<div class="flex">
    <!-- SIDEBAR -->
    <aside class="w-64 min-h-screen bg-gray-900 text-white flex flex-col fixed top-0 left-0 z-30">
        <div class="p-5 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-yellow-400 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heart text-gray-900 text-lg"></i>
                </div>
                <div>
                    <div class="font-bold text-yellow-400 text-sm leading-tight">Mission Sourire</div>
                    <div class="text-gray-400 text-xs">Administration</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.payments.dashboard') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg bg-yellow-400 text-gray-900 font-semibold text-sm">
                <i class="fas fa-chart-line w-5"></i><span>Tableau de bord</span>
            </a>
            <a href="{{ route('admin.payments.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-800 text-sm">
                <i class="fas fa-list w-5"></i><span>Tous les dons</span>
            </a>
            <a href="{{ route('admin.payments.export') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-800 text-sm">
                <i class="fas fa-file-csv w-5"></i><span>Exporter CSV</span>
            </a>
            <div class="border-t border-gray-700 my-3"></div>
            <a href="{{ route('home') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-800 text-sm">
                <i class="fas fa-globe w-5"></i><span>Voir le site</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-red-900 text-sm">
                    <i class="fas fa-sign-out-alt w-5"></i><span>Déconnexion</span>
                </button>
            </form>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <div class="text-xs text-gray-500">Connecté en tant que</div>
            <div class="text-sm font-medium text-gray-300 truncate">{{ Auth::user()->name }}</div>
        </div>
    </aside>

    <!-- CONTENU PRINCIPAL -->
    <main class="ml-64 flex-1 p-6">

        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tableau de bord</h1>
                <p class="text-gray-500 text-sm mt-1">
                    <i class="fas fa-clock mr-1"></i>
                    {{ now()->setTimezone('Africa/Porto-Novo')->format('d/m/Y à H:i') }} (GMT+1)
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.payments.export') }}"
                   class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center space-x-2">
                    <i class="fas fa-download"></i><span>Exporter CSV</span>
                </a>
                <a href="{{ route('admin.payments.index') }}"
                   class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-500 flex items-center space-x-2">
                    <i class="fas fa-list"></i><span>Tous les dons</span>
                </a>
            </div>
        </div>

        <!-- CARTES STATISTIQUES (4) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

            <!-- Aujourd'hui -->
            <div class="stat-card bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Aujourd'hui</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['daily']['count'] }}</p>
                        <p class="text-sm font-medium text-blue-600 mt-1">
                            {{ number_format($stats['daily']['amount'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Cette semaine -->
            <div class="stat-card bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Cette semaine</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['weekly']['count'] }}</p>
                        <p class="text-sm font-medium text-green-600 mt-1">
                            {{ number_format($stats['weekly']['amount'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Ce mois -->
            <div class="stat-card bg-white rounded-xl shadow p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Ce mois</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['monthly']['count'] }}</p>
                        <p class="text-sm font-medium text-purple-600 mt-1">
                            {{ number_format($stats['monthly']['amount'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total général -->
            <div class="stat-card bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl shadow p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-yellow-900 uppercase tracking-wide font-semibold">Total général</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">
                            {{ \App\Models\Payment::successful()->count() }}
                        </p>
                        <p class="text-sm font-bold text-gray-900 mt-1">
                            {{ number_format(\App\Models\Payment::successful()->sum('amount'), 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-300 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hand-holding-heart text-yellow-900 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAPHIQUES -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            <!-- Graphique 7 jours -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow p-5">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-semibold text-gray-800">Dons des 7 derniers jours</h2>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">Validés uniquement</span>
                </div>
                <canvas id="chartDons" height="100"></canvas>
            </div>

            <!-- Répartition méthodes -->
            <div class="bg-white rounded-xl shadow p-5">
                <h2 class="font-semibold text-gray-800 mb-4">Méthodes de paiement</h2>
                @if(count($stats['methods']) > 0)
                    @php $totalMethods = array_sum(array_column($stats['methods'], 'count')); @endphp
                    <div class="space-y-4">
                        @foreach($stats['methods'] as $method => $data)
                            @php
                                $pct = $totalMethods > 0 ? round(($data['count'] / $totalMethods) * 100) : 0;
                                $label = match(strtolower($method ?? '')) {
                                    'mtn'          => 'MTN Mobile Money',
                                    'moov'         => 'Moov Money',
                                    'mobile_money' => 'Mobile Money',
                                    'card'         => 'Carte bancaire',
                                    default        => ucfirst($method ?? 'Autre'),
                                };
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-700 font-medium">{{ $label }}</span>
                                    <span class="text-gray-500">{{ $data['count'] }} dons</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    {{ number_format($data['total'], 0, ',', ' ') }} FCFA – {{ $pct }}%
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400 py-8">
                        <i class="fas fa-chart-pie text-3xl mb-2"></i>
                        <p class="text-sm">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- DERNIERS DONS -->
        <div class="bg-white rounded-xl shadow">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="font-semibold text-gray-800">
                    <i class="fas fa-clock text-yellow-500 mr-2"></i>Derniers dons reçus
                </h2>
                <a href="{{ route('admin.payments.index') }}" class="text-sm text-yellow-600 hover:underline">
                    Voir tout <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-6 py-3 text-left">Contributeur</th>
                            <th class="px-6 py-3 text-left">Montant</th>
                            <th class="px-6 py-3 text-left">Statut</th>
                            <th class="px-6 py-3 text-left">Méthode</th>
                            <th class="px-6 py-3 text-left">Date</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentPayments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <div class="font-medium text-gray-800">
                                        {{ $payment->metadata['firstname'] ?? '' }}
                                        {{ $payment->metadata['lastname'] ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $payment->customer_email }}</div>
                                </td>
                                <td class="px-6 py-3 font-bold text-gray-800">
                                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $payment->status_badge['class'] }}">
                                        {{ $payment->status_badge['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500 text-xs uppercase">
                                    {{ $payment->payment_method ?? '—' }}
                                </td>
                                <td class="px-6 py-3 text-gray-400 text-xs">
                                    {{ $payment->formatted_created_at }}
                                </td>
                                <td class="px-6 py-3">
                                    <a href="{{ route('admin.payments.show', $payment->id) }}"
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                    <i class="fas fa-inbox text-3xl mb-2 block"></i>
                                    Aucun don enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<script>
const ctx = document.getElementById('chartDons').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartData['labels']),
        datasets: [
            {
                label: 'Nombre de dons',
                data: @json($chartData['counts']),
                backgroundColor: 'rgba(234, 179, 8, 0.6)',
                borderColor: 'rgb(234, 179, 8)',
                borderWidth: 1,
                borderRadius: 4,
                yAxisID: 'y',
            },
            {
                label: 'Montant (FCFA)',
                data: @json($chartData['amounts']),
                backgroundColor: 'rgba(59, 130, 246, 0.4)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1,
                borderRadius: 4,
                type: 'line',
                yAxisID: 'y1',
                tension: 0.3,
                fill: false,
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { position: 'top' } },
        scales: {
            y:  { beginAtZero: true, title: { display: true, text: 'Dons' } },
            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false },
                  title: { display: true, text: 'Montant FCFA' } }
        }
    }
});
</script>
</body>
</html>
