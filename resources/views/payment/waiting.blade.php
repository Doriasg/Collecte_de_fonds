<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification du paiement</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <!-- Animation -->
        <div class="mb-6">
            <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">⏳ Vérification en cours</h1>
        <p class="text-gray-600 mb-6">Nous confirmons le statut de votre paiement...</p>
        
        <!-- Détails -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
            <div class="flex justify-between mb-2">
                <span class="text-gray-700">Référence:</span>
                <span class="font-mono font-bold">{{ $payment->reference }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-gray-700">Montant:</span>
                <span class="font-bold text-green-600">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-700">Statut actuel:</span>
                <span class="font-bold text-yellow-600">{{ ucfirst($payment->status) }}</span>
            </div>
        </div>
        
        <!-- Message -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <p class="text-yellow-800 text-sm">
                <i class="fas fa-info-circle mr-2"></i>
                Cette vérification peut prendre quelques minutes. 
                Vous serez redirigé automatiquement.
            </p>
        </div>
        
        <!-- Vérification automatique -->
        <div id="checkingStatus" class="mt-4">
            <div class="flex items-center justify-center space-x-2">
                <div class="w-4 h-4 border-2 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
                <span class="text-sm text-gray-600">Vérification du statut...</span>
            </div>
        </div>
    </div>
    
    <script>
        // Vérifier le statut toutes les 5 secondes
        function checkPaymentStatus() {
            fetch("{{ route('payment.check-status', $payment->id) }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.status !== 'pending') {
                        // Si le statut a changé, rediriger
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
        
        // Vérifier immédiatement
        checkPaymentStatus();
        
        // Vérifier toutes les 5 secondes
        setInterval(checkPaymentStatus, 5000);
        
        // Rediriger après 60 secondes maximum
        setTimeout(() => {
            window.location.href = "{{ route('payment.success', $payment->id) }}";
        }, 60000);
    </script>
</body>
</html>