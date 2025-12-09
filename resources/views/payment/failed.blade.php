<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Échoué</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center">
            <!-- Icône d'erreur -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-times-circle text-red-500 text-4xl"></i>
                </div>
            </div>

            <!-- Titre -->
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Paiement Échoué
            </h1>
            
            <p class="text-gray-600 mb-8">
                Une erreur est survenue lors du traitement de votre paiement.
            </p>

            <!-- Détails de l'erreur -->
            <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8 text-left">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">
                            <i class="fas fa-hashtag mr-2"></i>Référence:
                        </span>
                        <span class="font-mono font-bold">{{ $payment->reference }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">
                            <i class="fas fa-money-bill-wave mr-2"></i>Montant:
                        </span>
                        <span class="font-bold text-red-600 text-lg">
                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Statut:
                        </span>
                        <span class="font-bold text-red-600 capitalize">{{ $payment->status }}</span>
                    </div>
                    
                    <div class="mt-4 p-3 bg-red-100 rounded-lg">
                        <p class="text-red-700 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            Raison possible : Transaction annulée, fonds insuffisants, ou problème réseau.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <!-- Réessayer -->
                <a href="{{ route('payment.create') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 text-center transform hover:-translate-y-1">
                    <i class="fas fa-redo mr-2"></i>
                    Réessayer le paiement
                </a>

                <!-- Contacter le support -->
                <a href="mailto:support@votredomaine.com?subject=Problème paiement {{ $payment->reference }}" 
                   class="block w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl transition duration-300 text-center">
                    <i class="fas fa-headset mr-2"></i>
                    Contacter le support
                </a>

                <!-- Retour à l'accueil -->
                <a href="{{ url('/') }}" 
                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-xl transition duration-300 text-center">
                    <i class="fas fa-home mr-2"></i>
                    Retour à l'accueil
                </a>
            </div>

            <!-- Conseils -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-bold text-gray-700 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>Conseils :
                </h3>
                <ul class="text-gray-600 text-sm text-left space-y-1">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        Vérifiez votre solde ou limite de carte
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        Essayez une autre méthode de paiement
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        Contactez votre banque si le problème persiste
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>