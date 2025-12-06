{{-- resources/views/payment/success.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-green-500 text-2xl"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Paiement Réussi !</h1>
        
        <p class="text-gray-600 mb-6">
            Merci beaucoup pour votre soutien ! Votre contribution est très appréciée.
        </p>
        
        @if(isset($transaction))
        <div class="bg-gray-50 rounded p-4 mb-6">
            <p class="text-gray-700">
                Référence: <strong>{{ $transaction->reference ?? 'N/A' }}</strong><br>
                Montant: <strong>{{ number_format($transaction->amount ?? 0, 0, ',', ' ') }} FCFA</strong>
            </p>
        </div>
        @endif
        
        <div class="space-y-3">
            <a href="{{ url('/') }}" 
               class="block w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition duration-300">
               Retour à l'accueil
            </a>
            
            <a href="{{ route('soutenir') }}" 
               class="block w-full border border-green-500 text-green-500 hover:bg-green-50 font-bold py-2 px-4 rounded transition duration-300">
               Faire un nouveau don
            </a>
        </div>
    </div>
</body>
</html>