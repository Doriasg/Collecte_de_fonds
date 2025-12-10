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
            <!-- Animation -->
            <div class="mb-6">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
            </div>

            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                <i class="fas fa-external-link-alt mr-2"></i>Redirection vers FedaPay
            </h1>
            
            <p class="text-gray-600 mb-6">
                Vous allez être redirigé vers la plateforme de paiement sécurisée...
            </p>

            <!-- Détails -->
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
                    <span class="font-bold status-badge" id="statusBadge">
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
                    Payer maintenant (redirection dans <span id="countdown">5</span>s)
                </a>

                <!-- Redirection manuelle -->
                <a href="{{ $payment->payment_url }}" 
                   target="_blank"
                   class="block w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded transition duration-300 text-center">
                    <i class="fas fa-credit-card mr-2"></i>
                    Ouvrir dans un nouvel onglet
                </a>
            </div>

            <!-- Vérification automatique -->
            <div id="statusCheck" class="mt-6 hidden">
                <div class="flex items-center justify-center space-x-2">
                    <div class="w-4 h-4 border-2 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                    <span class="text-sm text-gray-600">Vérification du statut...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let countdown = 5;
            const btn = document.getElementById('redirectBtn');
            const statusCheck = document.getElementById('statusCheck');
            const statusBadge = document.getElementById('statusBadge');
            
            // Mettre à jour le badge de statut
            function updateStatusBadge(status) {
                const colors = {
                    'approved': 'text-green-600',
                    'pending': 'text-yellow-600',
                    'canceled': 'text-red-600',
                    'declined': 'text-red-600'
                };
                
                statusBadge.className = 'font-bold ' + (colors[status] || 'text-yellow-600');
                statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            }
            
            // Redirection automatique
            const countdownInterval = setInterval(() => {
                countdown--;
                document.getElementById('countdown').textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "{{ $payment->payment_url }}";
                }
            }, 1000);
            
            // Vérification périodique du statut
            function checkPaymentStatus() {
                fetch("{{ route('payment.check-status', $payment->id) }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateStatusBadge(data.status);
                            
                            // Si le statut a changé et n'est plus "pending"
                            if (data.status !== 'pending') {
                                clearInterval(countdownInterval); // Arrêter le compte à rebours
                                
                                // Rediriger automatiquement selon le statut
                                if (data.redirect_url) {
                                    setTimeout(() => {
                                        window.location.href = data.redirect_url;
                                    }, 2000); // Attendre 2 secondes avant redirection
                                }
                            }
                        }
                    })
                    .catch(error => console.error('Erreur:', error));
            }
            
            // Vérifier immédiatement
            checkPaymentStatus();
            
            // Vérifier toutes les 5 secondes
            setInterval(checkPaymentStatus, 5000);
            
            // Montrer la vérification après 3 secondes
            setTimeout(() => {
                statusCheck.classList.remove('hidden');
            }, 3000);
        });
    </script>
</body>
</html>