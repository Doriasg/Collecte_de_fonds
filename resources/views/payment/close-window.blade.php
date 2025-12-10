<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fermeture en cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8 text-center">
        <!-- Animation -->
        <div class="mb-6">
            <div class="w-20 h-20 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
        </div>
        
        <h1 class="text-xl font-bold text-gray-800 mb-2">
            @if($payment->status === 'approved')
                ✅ Paiement Réussi
            @elseif($payment->status === 'pending')
                ⏳ Vérification en cours
            @else
                ❌ Transaction Terminée
            @endif
        </h1>
        
        <p class="text-gray-600 mb-6">
            @if($payment->status === 'approved')
                Votre paiement de {{ number_format($payment->amount, 0, ',', ' ') }} FCFA a été accepté.
            @elseif($payment->status === 'pending')
                Nous vérifions le statut de votre paiement...
            @else
                Statut : {{ ucfirst($payment->status) }}
            @endif
        </p>
        
        <!-- Détails -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
            <div class="flex justify-between mb-2">
                <span class="text-gray-600">Référence:</span>
                <span class="font-mono font-bold">{{ $payment->reference }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Montant:</span>
                <span class="font-bold text-green-600">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="space-y-3">
            <a href="{{ $redirect_url }}" 
               id="redirectLink"
               class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">
                Voir les détails
            </a>
            
            <button onclick="closeAndRedirect()" 
                    class="block w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition duration-300">
                Fermer cette fenêtre
            </button>
        </div>
        
        <!-- Auto-redirection -->
        <p class="text-sm text-gray-500 mt-6">
            Redirection automatique dans <span id="countdown">10</span> secondes...
        </p>
    </div>
    
    <script>
        let countdown = 10;
        const countdownElement = document.getElementById('countdown');
        const redirectLink = document.getElementById('redirectLink');
        
        // Mettre à jour le compte à rebours
        const timer = setInterval(() => {
            countdown--;
            countdownElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(timer);
                closeAndRedirect();
            }
        }, 1000);
        
        function closeAndRedirect() {
            try {
                // Essayer de fermer la fenêtre (si c'est un popup/iframe)
                window.close();
                
                // Si window.close() échoue, rediriger après 1 seconde
                setTimeout(() => {
                    window.location.href = "{{ $redirect_url }}";
                }, 1000);
                
            } catch (e) {
                // Si on ne peut pas fermer, rediriger directement
                window.location.href = "{{ $redirect_url }}";
            }
        }
        
        // Rediriger automatiquement après 10 secondes
        setTimeout(closeAndRedirect, 10000);
    </script>
</body>
</html>