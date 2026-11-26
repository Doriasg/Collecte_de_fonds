<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Don #{{ $payment->reference }} – Admin</title>
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

        <!-- Fil d'Ariane -->
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-5">
            <a href="{{ route('admin.payments.dashboard') }}" class="hover:text-yellow-600">Dashboard</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('admin.payments.index') }}" class="hover:text-yellow-600">Dons</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-800 font-medium">{{ $payment->reference }}</span>
        </div>

        <!-- Toast notifications -->
        <div id="toast" class="hidden fixed top-5 right-5 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium"></div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- COLONNE PRINCIPALE -->
            <div class="lg:col-span-2 space-y-5">

                <!-- Carte statut -->
                <div class="bg-white rounded-xl shadow p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">Don #{{ $payment->reference }}</h1>
                            <p class="text-sm text-gray-400 mt-1 font-mono">{{ $payment->transaction_id }}</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm font-bold {{ $payment->status_badge['class'] }}">
                            {{ $payment->status_badge['label'] }}
                            @if($payment->is_refunded)
                                <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-800 rounded-full text-xs">Remboursé</span>
                            @endif
                        </span>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Montant</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Méthode</p>
                            <p class="text-lg font-semibold text-gray-800 uppercase">
                                {{ $payment->payment_method ?? 'Non spécifié' }}
                            </p>
                        </div>
                    </div>

                    @if($payment->isSuccessful())
                        <div class="mt-4 flex items-center space-x-2 text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2.5 text-sm">
                            <i class="fas fa-check-circle"></i>
                            <span>Paiement confirmé le <strong>{{ $payment->formatted_paid_at }}</strong></span>
                        </div>
                    @endif
                </div>

                <!-- Infos contributeur -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-yellow-500 mr-2"></i>Contributeur
                    </h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400 mb-0.5">Nom complet</p>
                            <p class="font-semibold text-gray-800">
                                {{ $payment->metadata['firstname'] ?? '—' }}
                                {{ $payment->metadata['lastname'] ?? '' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-0.5">Email</p>
                            <p class="font-medium text-gray-800">{{ $payment->customer_email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 mb-0.5">Téléphone</p>
                            <p class="font-medium text-gray-800">{{ $payment->customer_phone }}</p>
                        </div>
                        @if(!empty($payment->metadata['description']))
                            <div>
                                <p class="text-gray-400 mb-0.5">Message</p>
                                <p class="font-medium text-gray-800">{{ $payment->metadata['description'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Détails transaction -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-yellow-500 mr-2"></i>Détails de la transaction
                    </h2>
                    <div class="divide-y text-sm">
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-500">Date de création</span>
                            <span class="font-medium">{{ $payment->formatted_created_at }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-500">Référence interne</span>
                            <span class="font-mono font-medium">{{ $payment->reference }}</span>
                        </div>
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-500">ID FedaPay</span>
                            <span class="font-mono text-xs">{{ $payment->transaction_id }}</span>
                        </div>
                        @if($payment->transaction_reference)
                            <div class="flex justify-between py-2.5">
                                <span class="text-gray-500">Référence transaction</span>
                                <span class="font-mono text-xs">{{ $payment->transaction_reference }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between py-2.5">
                            <span class="text-gray-500">Devise</span>
                            <span class="font-medium">{{ $payment->currency }}</span>
                        </div>
                        @if($payment->is_refunded)
                            <div class="flex justify-between py-2.5">
                                <span class="text-gray-500">Date remboursement</span>
                                <span class="font-medium text-purple-600">
                                    {{ $payment->refunded_at?->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>Notes internes
                    </h2>
                    <form id="noteForm">
                        <textarea id="notesInput" name="notes" rows="3"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 resize-none"
                            placeholder="Ajouter une note interne...">{{ $payment->notes }}</textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit"
                                class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-500">
                                <i class="fas fa-save mr-1"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- COLONNE ACTIONS -->
            <div class="space-y-5">

                <!-- Actions rapides -->
                <div class="bg-white rounded-xl shadow p-5">
                    <h2 class="font-semibold text-gray-800 mb-4">Actions</h2>
                    <div class="space-y-2.5">

                        <!-- Voir le reçu PDF -->
                        @if($payment->isSuccessful())
                            <a href="{{ route('receipt.download', $payment->reference) }}"
                               target="_blank"
                               class="w-full flex items-center justify-center space-x-2 bg-blue-600 text-white py-2.5 px-4 rounded-lg text-sm hover:bg-blue-700">
                                <i class="fas fa-file-pdf"></i><span>Voir le reçu PDF</span>
                            </a>

                            <!-- Renvoyer WhatsApp -->
                            <button onclick="resendReceipt({{ $payment->id }})"
                                class="w-full flex items-center justify-center space-x-2 bg-green-600 text-white py-2.5 px-4 rounded-lg text-sm hover:bg-green-700">
                                <i class="fab fa-whatsapp"></i><span>Renvoyer reçu WhatsApp</span>
                            </button>
                        @endif

                        <!-- Copier référence -->
                        <button onclick="copyRef('{{ $payment->reference }}')"
                            class="w-full flex items-center justify-center space-x-2 bg-gray-100 text-gray-800 py-2.5 px-4 rounded-lg text-sm hover:bg-gray-200">
                            <i class="fas fa-copy"></i><span>Copier la référence</span>
                        </button>

                        <!-- Lien de paiement -->
                        @if($payment->payment_url && $payment->isPending())
                            <a href="{{ $payment->payment_url }}" target="_blank"
                               class="w-full flex items-center justify-center space-x-2 bg-indigo-600 text-white py-2.5 px-4 rounded-lg text-sm hover:bg-indigo-700">
                                <i class="fas fa-external-link-alt"></i><span>Lien de paiement FedaPay</span>
                            </a>
                        @endif

                        <!-- Rembourser -->
                        @if($payment->isSuccessful() && !$payment->is_refunded)
                            <button onclick="confirmRefund({{ $payment->id }})"
                                class="w-full flex items-center justify-center space-x-2 bg-red-50 text-red-700 border border-red-200 py-2.5 px-4 rounded-lg text-sm hover:bg-red-100">
                                <i class="fas fa-undo"></i><span>Marquer comme remboursé</span>
                            </button>
                        @endif

                        <!-- Retour -->
                        <a href="{{ route('admin.payments.index') }}"
                           class="w-full flex items-center justify-center space-x-2 bg-gray-100 text-gray-700 py-2.5 px-4 rounded-lg text-sm hover:bg-gray-200">
                            <i class="fas fa-arrow-left"></i><span>Retour à la liste</span>
                        </a>
                    </div>
                </div>

                <!-- Données FedaPay brutes -->
                @if($fedapayData)
                    <div class="bg-white rounded-xl shadow p-5">
                        <h2 class="font-semibold text-gray-800 mb-3 text-sm">
                            <i class="fas fa-database text-gray-400 mr-1"></i>Données FedaPay
                        </h2>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between py-1 border-b border-gray-50">
                                <span class="text-gray-500">ID</span>
                                <span class="font-mono font-medium">{{ $fedapayData->id }}</span>
                            </div>
                            @if(isset($fedapayData->mode))
                                <div class="flex justify-between py-1 border-b border-gray-50">
                                    <span class="text-gray-500">Mode</span>
                                    <span>{{ $fedapayData->mode }}</span>
                                </div>
                            @endif
                            @if(isset($fedapayData->operation))
                                <div class="flex justify-between py-1 border-b border-gray-50">
                                    <span class="text-gray-500">Opération</span>
                                    <span>{{ $fedapayData->operation }}</span>
                                </div>
                            @endif
                        </div>
                        <button onclick="toggleRaw()"
                            class="mt-3 text-xs text-blue-600 hover:underline w-full text-left">
                            <i class="fas fa-code mr-1"></i>Voir données brutes
                        </button>
                        <pre id="rawData" class="hidden mt-2 p-3 bg-gray-900 text-green-400 rounded text-xs overflow-auto max-h-48">{{ json_encode($fedapayData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif

                <!-- Résumé timeline -->
                <div class="bg-white rounded-xl shadow p-5">
                    <h2 class="font-semibold text-gray-800 mb-4 text-sm">Chronologie</h2>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="w-7 h-7 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-plus text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-700">Don créé</p>
                                <p class="text-xs text-gray-400">{{ $payment->formatted_created_at }}</p>
                            </div>
                        </div>
                        @if($payment->isSuccessful())
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-check text-green-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Paiement confirmé</p>
                                    <p class="text-xs text-gray-400">{{ $payment->formatted_paid_at }}</p>
                                </div>
                            </div>
                        @elseif($payment->isFailed())
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-times text-red-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Paiement échoué</p>
                                    <p class="text-xs text-gray-400 capitalize">{{ $payment->status }}</p>
                                </div>
                            </div>
                        @endif
                        @if($payment->is_refunded)
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-undo text-purple-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-700">Remboursé</p>
                                    <p class="text-xs text-gray-400">{{ $payment->refunded_at?->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = `fixed top-5 right-5 z-50 px-5 py-3 rounded-lg shadow-lg text-white text-sm font-medium ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 3500);
}

function copyRef(ref) {
    navigator.clipboard.writeText(ref).then(() => showToast('Référence copiée : ' + ref));
}

function toggleRaw() {
    document.getElementById('rawData').classList.toggle('hidden');
}

function confirmRefund(id) {
    if (!confirm('Marquer ce don comme remboursé ? Cette action est irréversible.')) return;
    fetch(`/admin/payments/${id}/refund`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) { showToast(d.message); setTimeout(() => location.reload(), 1500); }
        else showToast(d.message, 'error');
    })
    .catch(() => showToast('Erreur réseau', 'error'));
}

function resendReceipt(id) {
    if (!confirm('Renvoyer le reçu PDF par WhatsApp ?')) return;
    fetch(`/admin/payments/${id}/resend-receipt`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(d => showToast(d.message, d.success ? 'success' : 'error'))
    .catch(() => showToast('Erreur réseau', 'error'));
}

document.getElementById('noteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(`/admin/payments/{{ $payment->id }}/notes`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json' },
        body: JSON.stringify({ notes: document.getElementById('notesInput').value })
    })
    .then(r => r.json())
    .then(d => showToast(d.message, d.success ? 'success' : 'error'))
    .catch(() => showToast('Erreur réseau', 'error'));
});
</script>
</body>
</html>
