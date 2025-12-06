<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soutenir notre projet - Paiement Sécurisé</title>
    <!-- Tailwind CSS (optionnel mais recommandé) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-card {
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        .input-field {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }
        .amount-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .amount-option:hover {
            background-color: #f7fafc;
        }
        .amount-option.selected {
            background-color: #4299e1;
            color: white;
            border-color: #4299e1;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-4xl w-full mx-auto">
        <!-- En-tête -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                <i class="fas fa-heart text-red-500 mr-3"></i>
                Soutenez notre projet
            </h1>
            <p class="text-gray-600 text-lg">
                Votre contribution nous aide à continuer notre mission. Chaque don compte !
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Formulaire de paiement -->
            <div class="payment-card bg-white rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">
                    <i class="fas fa-credit-card text-blue-500 mr-2"></i>
                    Informations de paiement
                </h2>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <!-- Montant à donner -->
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-3">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Montant du soutien (FCFA)
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
                            <div class="amount-option border-2 border-gray-200 rounded-lg p-4 text-center font-semibold text-gray-700" 
                                 data-amount="1000">
                                1 000 FCFA
                            </div>
                            <div class="amount-option border-2 border-gray-200 rounded-lg p-4 text-center font-semibold text-gray-700" 
                                 data-amount="5000">
                                5 000 FCFA
                            </div>
                            <div class="amount-option border-2 border-gray-200 rounded-lg p-4 text-center font-semibold text-gray-700" 
                                 data-amount="10000">
                                10 000 FCFA
                            </div>
                            <div class="amount-option border-2 border-gray-200 rounded-lg p-4 text-center font-semibold text-gray-700" 
                                 data-amount="20000">
                                20 000 FCFA
                            </div>
                        </div>
                        <input type="hidden" name="amount" id="amount" value="1000" required>
                        
                        <!-- Montant personnalisé -->
                        <div class="mt-4">
                            <label class="block text-gray-700 mb-2">Ou montant personnalisé :</label>
                            <div class="relative">
                                <input type="number" 
                                       id="customAmount" 
                                       min="100" 
                                       step="100" 
                                       placeholder="Entrez un montant" 
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg input-field pl-12">
                                <div class="absolute left-4 top-3 text-gray-500">
                                    FCFA
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations personnelles -->
                    <div class="space-y-5">
                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-user mr-2"></i>
                                Nom complet
                            </label>
                            <input type="text" 
                                   name="full_name" 
                                   placeholder="Votre nom complet" 
                                   required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg input-field"
                                   value="{{ old('full_name') }}">
                            @error('full_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-envelope mr-2"></i>
                                Adresse email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   placeholder="votre@email.com" 
                                   required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg input-field"
                                   value="{{ old('email') }}">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">
                                <i class="fas fa-phone mr-2"></i>
                                Numéro de téléphone
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   placeholder="Ex: (+229) 01 23 45 67 89" 
                                   required
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg input-field"
                                   value="{{ old('phone') }}">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" 
                            id="submitBtn"
                            class="w-full mt-8 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fas fa-lock mr-2"></i>
                        Payer en toute sécurité
                        <span id="amountDisplay" class="ml-2">(1 000 FCFA)</span>
                    </button>

                    <!-- Sécurité -->
                    <div class="mt-6 text-center text-gray-500 text-sm">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Paiement 100% sécurisé par FedaPay
                    </div>
                </form>
            </div>

            <!-- Informations supplémentaires -->
            <div class="space-y-6">
                <!-- Méthodes de paiement acceptées -->
                <div class="bg-white rounded-2xl p-6 payment-card">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-mobile-alt mr-2"></i>
                        Méthodes de paiement acceptées
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg">
                            <i class="fab fa-cc-mastercard text-red-500 text-2xl"></i>
                            <span>Carte bancaire</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg">
                            <i class="fas fa-mobile text-green-500 text-2xl"></i>
                            <span>Mobile Money</span>
                        </div>
                        <div class="flex items-center space-x-2 bg-gray-50 px-4 py-2 rounded-lg">
                            <i class="fas fa-wallet text-purple-500 text-2xl"></i>
                            <span>Portefeuille électronique</span>
                        </div>
                    </div>
                </div>

                <!-- Avantages du don -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl p-6 text-white">
                    <h3 class="text-xl font-bold mb-4">
                        <i class="fas fa-star mr-2"></i>
                        Avantages de votre soutien
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-300"></i>
                            <span>Receipt fiscal disponible</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-300"></i>
                            <span>Accès aux mises à jour exclusives</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-300"></i>
                            <span>Reconnaissance sur notre page dédiée</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-green-300"></i>
                            <span>Satisfaction de contribuer à une bonne cause</span>
                        </li>
                    </ul>
                </div>

                <!-- Sécurité -->
                <div class="bg-white rounded-2xl p-6 payment-card">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                        Sécurité garantie
                    </h3>
                    <p class="text-gray-600">
                        <i class="fas fa-lock text-green-500 mr-2"></i>
                        Toutes vos informations sont cryptées et protégées par FedaPay.
                    </p>
                    <p class="text-gray-600 mt-2">
                        <i class="fas fa-user-shield text-blue-500 mr-2"></i>
                        Aucune information bancaire n'est stockée sur nos serveurs.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center text-gray-500 text-sm">
            <p>
                <i class="fas fa-question-circle mr-2"></i>
                Des questions ? Contactez-nous à 
                <a href="mailto:support@votredomaine.com" class="text-blue-500 hover:underline">
                    support@votredomaine.com
                </a>
            </p>
            <p class="mt-2">
                En cliquant sur "Payer en toute sécurité", vous acceptez nos 
                <a href="#" class="text-blue-500 hover:underline">conditions d'utilisation</a> 
                et notre 
                <a href="#" class="text-blue-500 hover:underline">politique de confidentialité</a>.
            </p>
        </div>
    </div>

    <script>
        // Gestion des montants prédéfinis
        document.querySelectorAll('.amount-option').forEach(option => {
            option.addEventListener('click', function() {
                // Retirer la classe selected de toutes les options
                document.querySelectorAll('.amount-option').forEach(opt => {
                    opt.classList.remove('selected', 'bg-blue-500', 'text-white');
                    opt.classList.add('border-gray-200', 'text-gray-700');
                });
                
                // Ajouter la classe selected à l'option cliquée
                this.classList.add('selected', 'bg-blue-500', 'text-white');
                this.classList.remove('border-gray-200', 'text-gray-700');
                
                // Mettre à jour le champ caché
                const amount = this.getAttribute('data-amount');
                document.getElementById('amount').value = amount;
                document.getElementById('amountDisplay').textContent = `(${formatAmount(amount)} FCFA)`;
                
                // Réinitialiser le champ personnalisé
                document.getElementById('customAmount').value = '';
            });
        });

        // Gestion du montant personnalisé
        document.getElementById('customAmount').addEventListener('input', function() {
            const customAmount = this.value;
            
            if (customAmount) {
                // Retirer la sélection des montants prédéfinis
                document.querySelectorAll('.amount-option').forEach(opt => {
                    opt.classList.remove('selected', 'bg-blue-500', 'text-white');
                    opt.classList.add('border-gray-200', 'text-gray-700');
                });
                
                // Mettre à jour le champ caché et l'affichage
                document.getElementById('amount').value = customAmount;
                document.getElementById('amountDisplay').textContent = `(${formatAmount(customAmount)} FCFA)`;
            }
        });

        // Validation du formulaire
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            const amount = document.getElementById('amount').value;
            const minAmount = 100;
            
            if (amount < minAmount) {
                e.preventDefault();
                alert(`Le montant minimum est de ${minAmount} FCFA`);
                return false;
            }
            
            // Afficher un indicateur de chargement
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement en cours...';
            submitBtn.disabled = true;
        });

        // Formatage des montants
        function formatAmount(amount) {
            return parseInt(amount).toLocaleString('fr-FR');
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Sélectionner le premier montant par défaut
            document.querySelector('.amount-option').click();
        });
    </script>
</body>
</html>