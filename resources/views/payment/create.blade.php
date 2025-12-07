<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement en ligne</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- En-tête -->
            <div class="bg-blue-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">
                    <i class="fas fa-lock mr-2"></i>Paiement Sécurisé
                </h1>
                <p class="text-blue-100 text-sm mt-1">Remplissez le formulaire pour procéder au paiement</p>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('payment.process') }}" method="POST" class="p-6">
                @csrf
                
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Montant -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-money-bill-wave mr-2"></i>Montant (FCFA)
                    </label>
                    <input type="number" name="amount" 
                           value="{{ old('amount', 1000) }}"
                           min="100" step="100"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informations personnelles -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Prénom
                        </label>
                        <input type="text" name="firstname" 
                               value="{{ old('firstname', auth()->user()->firstname ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-user mr-2"></i>Nom
                        </label>
                        <input type="text" name="lastname" 
                               value="{{ old('lastname', auth()->user()->lastname ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </label>
                    <input type="email" name="email" 
                           value="{{ old('email', auth()->user()->email ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-phone mr-2"></i>Téléphone
                    </label>
                    <div class="flex">
                        <div class="bg-gray-100 px-3 py-2 border border-r-0 border-gray-300 rounded-l">
                            +229
                        </div>
                        <input type="text" name="phone" 
                               value="{{ old('phone', auth()->user()->phone ?? '') }}"
                               placeholder="90123456"
                               class="w-full px-3 py-2 border border-gray-300 rounded-r focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Format: +22990123456</p>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-file-alt mr-2"></i>Description
                    </label>
                    <textarea name="description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Objet du paiement...">{{ old('description') }}</textarea>
                </div>

                <!-- Bouton de soumission -->
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-300">
                    <i class="fas fa-credit-card mr-2"></i>Procéder au paiement
                </button>

                <!-- Sécurité -->
                <div class="mt-4 text-center">
                    <p class="text-gray-500 text-sm">
                        <i class="fas fa-shield-alt text-green-500 mr-1"></i>
                        Paiement 100% sécurisé par FedaPay
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>