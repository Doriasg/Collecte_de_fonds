<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-green-50 to-blue-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        <div class="text-center">
            <!-- Icône de succès -->
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                </div>
            </div>

            <!-- Titre -->
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Paiement Réussi !
            </h1>
            
            <p class="text-gray-600 mb-8">
                Votre paiement a été traité avec succès.
            </p>

            <!-- Carte de confirmation -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-xl p-6 mb-8 text-left">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">
                            <i class="fas fa-hashtag mr-2"></i>Référence:
                        </span>
                        <span class="font-mono font-bold text-gray-800">{{ $payment->reference }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">
                            <i class="fas fa-money-bill-wave mr-2"></i>Montant:
                        </span>
                        <span class="font-bold text-green-600 text-lg">
                            {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">
                            <i class="fas fa-calendar-check mr-2"></i>Date:
                        </span>
                        <span class="font-medium">{{ $payment->paid_at->format('d/m/Y H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">
                            <i class="fas fa-credit-card mr-2"></i>Méthode:
                        </span>
                        <span class="font-medium capitalize">{{ $payment->payment_method ?? 'Mobile Money' }}</span>
                    </div>
                </div>
            </div>

            <!-- Recevoir un reçu par email -->
            <div class="mb-8">
                <p class="text-gray-600 mb-4">
                    <i class="fas fa-envelope text-blue-500 mr-2"></i>
                    Un reçu a été envoyé à <strong>{{ $payment->customer_email }}</strong>
                </p>
            </div>

            <!-- Actions -->
            <div class="space-y-4">
                <!-- Retour à l'accueil -->
                <a href="{{ url('/') }}" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition duration-300 text-center transform hover:-translate-y-1">
                    <i class="fas fa-home mr-2"></i>
                    Retour à l'accueil
                </a>

                <!-- Télécharger le reçu -->
                <button onclick="window.print()"
                   class="block w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-4 rounded-xl transition duration-300 text-center">
                    <i class="fas fa-print mr-2"></i>
                    Imprimer le reçu
                </button>
            </div>

            <!-- Message de sécurité -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-500 text-sm">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    Transaction sécurisée et cryptée. Merci pour votre confiance.
                </p>
            </div>
        </div>
    </div>

    <!-- Script pour impression -->
    <script>
        // Auto-scroll pour impression
        window.onbeforeprint = function() {
            document.body.classList.add('printing');
        };
        
        window.onafterprint = function() {
            document.body.classList.remove('printing');
        };
    </script>
    
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .max-w-md, .max-w-md * {
                visibility: visible;
            }
            .max-w-md {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
            }
            button {
                display: none !important;
            }
        }
    </style>
</body>
</html>