<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des dons – Mission Sourire Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <div class="font-bold text-yellow-400 text-sm">Mission Sourire</div>
                    <div class="text-gray-400 text-xs">Administration</div>
                </div>
            </div>
        </div>
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.payments.dashboard') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-gray-300 hover:bg-gray-800 text-sm">
                <i class="fas fa-chart-line w-5"></i><span>Tableau de bord</span>
            </a>
            <a href="{{ route('admin.payments.index') }}"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-lg bg-yellow-400 text-gray-900 font-semibold text-sm">
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

    <!-- CONTENU -->
    <main class="ml-64 flex-1 p-6">

        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Tous les dons</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $stats['total'] }} dons enregistrés</p>
            </div>
            <a href="{{ route('admin.payments.export') }}"
               class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 flex items-center space-x-2">
                <i class="fas fa-download"></i><span>Exporter CSV</span>
            </a>
        </div>

        <!-- STATS RAPIDES -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow px-4 py-3 flex items-center space-x-3">
                <i class="fas fa-receipt text-blue-500 text-xl"></i>
                <div>
                    <p class="text-xs text-gray-400">Total</p>
                    <p class="font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow px-4 py-3 flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                <div>
                    <p class="text-xs text-gray-400">Réussis</p>
                    <p class="font-bold text-green-700">{{ $stats['successful'] }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow px-4 py-3 flex items-center space-x-3">
                <i class="fas fa-clock text-yellow-500 text-xl"></i>
                <div>
                    <p class="text-xs text-gray-400">En attente</p>
                    <p class="font-bold text-yellow-700">{{ $stats['pending'] }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow px-4 py-3 flex items-center space-x-3">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
                <div>
                    <p class="text-xs text-gray-400">Échecs</p>
                    <p class="font-bold text-red-700">{{ $stats['failed'] }}</p>
                </div>
            </div>
        </div>

        <!-- TOTAL COLLECTÉ -->
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-xl shadow p-4 mb-6 flex justify-between items-center">
            <div>
                <p class="text-sm font-semibold text-yellow-900">Total collecté (dons validés)</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">
                    {{ number_format($stats['total_amount'], 0, ',', ' ') }} FCFA
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-yellow-900">Aujourd'hui</p>
                <p class="text-xl font-bold text-gray-900">
                    {{ number_format($stats['today_amount'], 0, ',', ' ') }} FCFA
                </p>
                <p class="text-xs text-yellow-800">{{ $stats['today'] }} don(s)</p>
            </div>
        </div>

        <!-- FILTRES -->
        <div class="bg-white rounded-xl shadow p-4 mb-5">
            <form method="GET" action="{{ route('admin.payments.index') }}"
                  class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Statut</label>
                    <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                        <option value="all"        {{ request('status','all') == 'all'        ? 'selected' : '' }}>Tous</option>
                        <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Réussis</option>
                        <option value="pending"    {{ request('status') == 'pending'    ? 'selected' : '' }}>En attente</option>
                        <option value="failed"     {{ request('status') == 'failed'     ? 'selected' : '' }}>Échecs</option>
                        <option value="refunded"   {{ request('status') == 'refunded'   ? 'selected' : '' }}>Remboursés</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Du</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Au</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Email, tél., référence..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>
                <div class="flex space-x-2">
                    <button type="submit"
                        class="flex-1 bg-yellow-400 text-gray-900 font-semibold py-2 rounded-lg text-sm hover:bg-yellow-500">
                        <i class="fas fa-search mr-1"></i>Filtrer
                    </button>
                    <a href="{{ route('admin.payments.index') }}"
                       class="px-3 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- TABLEAU -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-5 py-3 text-left">Référence</th>
                            <th class="px-5 py-3 text-left">Contributeur</th>
                            <th class="px-5 py-3 text-right">Montant</th>
                            <th class="px-5 py-3 text-left">Statut</th>
                            <th class="px-5 py-3 text-left">Méthode</th>
                            <th class="px-5 py-3 text-left">Date</th>
                            <th class="px-5 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-yellow-50 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="font-mono font-medium text-gray-800 text-xs">{{ $payment->reference }}</div>
                                    <div class="text-xs text-gray-400 truncate max-w-[140px]">{{ $payment->transaction_id }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-medium text-gray-800">
                                        {{ $payment->metadata['firstname'] ?? '' }}
                                        {{ $payment->metadata['lastname'] ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-400">{{ $payment->customer_email }}</div>
                                    <div class="text-xs text-gray-400">{{ $payment->customer_phone }}</div>
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-gray-800">
                                    {{ number_format($payment->amount, 0, ',', ' ') }}
                                    <span class="text-xs text-gray-400 font-normal">FCFA</span>
                                </td>
                                <td class="px-5 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $payment->status_badge['class'] }}">
                                        {{ $payment->status_badge['label'] }}
                                    </span>
                                    @if($payment->is_refunded)
                                        <span class="mt-1 block px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs w-fit">
                                            Remboursé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-500 uppercase">
                                    {{ $payment->payment_method ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-500">
                                    {{ $payment->formatted_created_at }}
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.payments.show', $payment->id) }}"
                                           class="text-blue-600 hover:text-blue-800" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($payment->isSuccessful())
                                            <a href="{{ route('receipt.download', $payment->reference) }}"
                                               target="_blank"
                                               class="text-gray-500 hover:text-gray-700" title="Reçu PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                    <p class="text-sm">Aucun don trouvé</p>
                                    @if(request()->hasAny(['status','search','date_from','date_to']))
                                        <a href="{{ route('admin.payments.index') }}" class="text-yellow-600 text-sm mt-1 inline-block">
                                            Effacer les filtres
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if($payments->hasPages())
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $payments->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

</body>
</html>
