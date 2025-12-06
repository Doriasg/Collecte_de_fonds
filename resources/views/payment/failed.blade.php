{{-- resources/views/payment/failed.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Échoué</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-red-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-times text-red-500 text-2xl"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Paiement Échoué</h1>
        
        <p class="text-gray-600 mb-6">
            {{ $message ?? 'Une erreur est survenue lors du traitement de votre paiement.' }}
        </p>
        
        <p class="text-gray-500 text-sm mb-6">
            Veuillez réessayer ou contacter le support si le problème persiste.
        </p>
        
        <div class="space-y-3">
            <a href="{{ route('soutenir') }}" 
               class="block w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300">
               Réessayer
            </a>
            
            <a href="{{ url('/') }}" 
               class="block w-full border border-gray-300 text-gray-700 hover:bg-gray-50 font-bold py-2 px-4 rounded transition duration-300">
               Retour à l'accueil
            </a>
        </div>
    </div>
</body>
</html>