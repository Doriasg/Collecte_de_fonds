<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirection vers le paiement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center">
            <!-- Animation de chargement -->
            <div class="mb-6">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
            </div>

            <!-- Titre -->
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-external-link-alt mr-2"></i>Redirection en cours
            </h1>
            
            <p class="text-gray-600 mb-6">
                Vous allez être redirigé vers la plateforme de paiement sécurisée FedaPay...
            </p>

            <!-- Détails du paiement -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Référence:</span>
                    <span class="font-mono font-bold">{{ $payment->reference }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Montant:</span>
                    <span class="font-bold text-green-600">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Statut:</span>
                    <span class="font-bold {{ $payment->status == 'pending' ? 'text-yellow-600' : 'text-blue-600' }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>

            <!-- Boutons -->
            <div class="space-y-3">
                <!-- Redirection automatique -->
                <a href="{{ $payment->payment_url }}" 
                   id="redirectBtn"
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-300 text-center">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Redirection automatique (5s)...
                </a>

                <!-- Redirection manuelle -->
                <a href="{{ $payment->payment_url }}" 
                   target="_blank"
                   class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded transition duration-300 text-center">
                    <i class="fas fa-credit-card mr-2"></i>
                    Cliquez ici si la redirection ne fonctionne pas
                </a>

                <!-- Retour -->
                <a href="{{ route('payment.create') }}" 
                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded transition duration-300 text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour
                </a>
            </div>

            <!-- Message -->
            <p class="text-gray-500 text-sm mt-6">
                <i class="fas fa-info-circle mr-1"></i>
                Ne fermez pas cette page pendant le paiement
            </p>
        </div>
    </div>

    <!-- Script de redirection automatique -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let countdown = 5;
            const btn = document.getElementById('redirectBtn');
            
            // Mise à jour du compte à rebours
            const interval = setInterval(() => {
                countdown--;
                btn.innerHTML = `<i class="fas fa-external-link-alt mr-2"></i>Redirection automatique (${countdown}s)...`;
                
                if (countdown <= 0) {
                    clearInterval(interval);
                    window.location.href = "{{ $payment->payment_url }}";
                }
            }, 1000);
            
            // Redirection après 5 secondes
            setTimeout(() => {
                window.location.href = "{{ $payment->payment_url }}";
            }, 5000);
            
            // Vérification périodique du statut
            const checkStatus = setInterval(() => {
                fetch("{{ route('payment.check-status', $payment->id) }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.is_successful) {
                            clearInterval(checkStatus);
                            window.location.href = "{{ route('payment.success', $payment->id) }}";
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }, 10000); // Toutes les 10 secondes
        });
    </script>
</body>
</html>