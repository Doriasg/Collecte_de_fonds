<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirection en cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800 mb-2">
                @if($payment->isSuccessful())
                    ✅ Paiement Réussi !
                @else
                    ⚠️ Transaction Terminée
                @endif
            </h1>
            <p class="text-gray-600 mb-4">
                @if($payment->isSuccessful())
                    Votre paiement de {{ number_format($payment->amount, 0, ',', ' ') }} FCFA a été traité.
                @else
                    Statut : {{ ucfirst($payment->status) }}
                @endif
            </p>
        </div>
        
        <div class="space-y-3">
            <a href="{{ $redirect_url }}" 
               class="block w-full bg-blue-600 text-white py-3 px-4 rounded hover:bg-blue-700">
                Voir les détails du paiement
            </a>
            
            <button onclick="closeWindow()" 
                    class="block w-full bg-gray-200 text-gray-800 py-3 px-4 rounded hover:bg-gray-300">
                Fermer cette fenêtre
            </button>
        </div>
        
        <p class="text-sm text-gray-500 mt-6">
            Référence : {{ $payment->reference }}
        </p>
    </div>
    
    <script>
        // Fermer la fenêtre après 3 secondes si l'utilisateur ne fait rien
        setTimeout(() => {
            window.close();
            // Si la fenêtre ne se ferme pas, rediriger
            setTimeout(() => {
                window.location.href = "{{ $redirect_url }}";
            }, 1000);
        }, 3000);
        
        function closeWindow() {
            window.close();
            window.location.href = "{{ $redirect_url }}";
        }
    </script>
</body>
</html>