<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test FedaPay Sandbox</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Test FedaPay Configuration</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Carte Sandbox -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-yellow-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-vial text-yellow-600 text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Mode Sandbox (Test)</h2>
                        <p class="text-gray-600">Pour le développement et les tests</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="p-3 bg-yellow-50 rounded-lg">
                        <p class="font-medium text-yellow-800">Clés nécessaires:</p>
                        <code class="text-sm text-gray-700 block mt-1">sk_sandbox_xxxxxxxxxxxx</code>
                        <code class="text-sm text-gray-700 block">pk_sandbox_xxxxxxxxxxxx</code>
                    </div>
                    
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="font-medium text-blue-800">URL API:</p>
                        <code class="text-sm text-gray-700">https://sandbox-api.fedapay.com</code>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg">
                        <p class="font-medium text-green-800">Avantages:</p>
                        <ul class="text-sm text-gray-700 list-disc pl-5 mt-1">
                            <li>Aucun paiement réel</li>
                            <li>Tests illimités</li>
                            <li>Pas besoin de fonds</li>
                        </ul>
                    </div>
                </div>
                
                <button onclick="testSandbox()" 
                        class="w-full mt-6 bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg transition">
                    Tester Sandbox
                </button>
                
                <button onclick="switchToSandbox()" 
                        class="w-full mt-3 border border-yellow-500 text-yellow-500 hover:bg-yellow-50 font-bold py-3 px-4 rounded-lg transition">
                    Passer en Sandbox
                </button>
            </div>
            
            <!-- Carte Live -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-red-200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-bolt text-red-600 text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Mode Live (Production)</h2>
                        <p class="text-gray-600">Pour les paiements réels</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="p-3 bg-red-50 rounded-lg">
                        <p class="font-medium text-red-800">⚠️ Attention:</p>
                        <p class="text-sm text-gray-700 mt-1">Les transactions sont réelles et irréversibles</p>
                    </div>
                    
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <p class="font-medium text-purple-800">Prérequis:</p>
                        <ul class="text-sm text-gray-700 list-disc pl-5 mt-1">
                            <li>Compte FedaPay vérifié</li>
                            <li>Fonds suffisants</li>
                            <li>Certificat SSL (HTTPS)</li>
                        </ul>
                    </div>
                    
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="font-medium text-gray-800">Clés nécessaires:</p>
                        <code class="text-sm text-gray-700 block mt-1">sk_live_xxxxxxxxxxxx</code>
                        <code class="text-sm text-gray-700 block">pk_live_xxxxxxxxxxxx</code>
                    </div>
                </div>
                
                <div class="mt-6 space-y-3">
                    <button onclick="testLive()" 
                            class="w-full bg-gray-300 text-gray-600 font-bold py-3 px-4 rounded-lg cursor-not-allowed"
                            disabled
                            title="Non disponible - Passez d'abord en sandbox">
                        Tester Live (désactivé)
                    </button>
                    
                    <p class="text-xs text-gray-500 text-center">
                        Activez uniquement après avoir tout testé en sandbox
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Résultats -->
        <div id="results" class="mt-8"></div>
        
        <!-- Configuration actuelle -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Configuration actuelle</h3>
            <div id="currentConfig" class="space-y-2">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Mode:</span>
                    <span id="currentMode" class="font-bold">Chargement...</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Clé API:</span>
                    <span id="currentKey" class="text-sm font-mono">Chargement...</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <span class="font-medium">Statut:</span>
                    <span id="currentStatus" class="font-medium">Chargement...</span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Charger la configuration actuelle
        async function loadCurrentConfig() {
            try {
                const response = await fetch('/api/fedapay/config');
                const data = await response.json();
                
                document.getElementById('currentMode').textContent = data.mode;
                document.getElementById('currentKey').textContent = data.key_preview;
                document.getElementById('currentStatus').textContent = data.is_live ? 
                    '🟢 Production' : '🟡 Sandbox';
                    
                document.getElementById('currentStatus').className = 
                    data.is_live ? 'font-bold text-green-600' : 'font-bold text-yellow-600';
                    
            } catch (error) {
                console.error('Erreur chargement config:', error);
            }
        }
        
        // Tester le sandbox
        async function testSandbox() {
            const results = document.getElementById('results');
            results.innerHTML = `
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-center">
                        <i class="fas fa-spinner fa-spin text-blue-500 mr-3"></i>
                        <span class="font-medium">Test Sandbox en cours...</span>
                    </div>
                </div>
            `;
            
            try {
                const response = await fetch('{{ route("test.feda") }}');
                const data = await response.json();
                
                if (data.success) {
                    results.innerHTML = `
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                                <h3 class="text-lg font-bold text-green-800">✅ Test Sandbox réussi!</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="p-3 bg-white rounded-lg">
                                    <p class="font-medium">Client ID:</p>
                                    <code class="text-sm">${data.results.customer_id}</code>
                                </div>
                                <div class="p-3 bg-white rounded-lg">
                                    <p class="font-medium">Transaction ID:</p>
                                    <code class="text-sm">${data.results.transaction_id}</code>
                                </div>
                                <div class="p-3 bg-white rounded-lg">
                                    <p class="font-medium">URL de paiement:</p>
                                    <a href="${data.results.token_url}" target="_blank" class="text-blue-600 hover:underline text-sm block truncate">
                                        ${data.results.token_url}
                                    </a>
                                </div>
                            </div>
                            <p class="mt-4 text-green-700 font-medium">${data.next_steps}</p>
                        </div>
                    `;
                } else {
                    results.innerHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-times-circle text-red-500 text-2xl mr-3"></i>
                                <h3 class="text-lg font-bold text-red-800">❌ Test Sandbox échoué</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="p-3 bg-white rounded-lg">
                                    <p class="font-medium text-red-700">Erreur:</p>
                                    <p class="text-sm">${data.error || data.message}</p>
                                </div>
                                ${data.diagnostic ? `
                                <div class="p-3 bg-yellow-50 rounded-lg">
                                    <p class="font-medium">Diagnostic:</p>
                                    <pre class="text-xs mt-2 whitespace-pre-wrap">${JSON.stringify(data.diagnostic, null, 2)}</pre>
                                </div>
                                ` : ''}
                                ${data.solution ? `
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <p class="font-medium">Solution:</p>
                                    <p class="text-sm mt-1">${data.solution}</p>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                results.innerHTML = `
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                            <h3 class="text-lg font-bold text-red-800">❌ Erreur réseau</h3>
                        </div>
                        <p class="text-red-700">Impossible de contacter le serveur</p>
                        <p class="text-sm text-gray-600 mt-2">${error.message}</p>
                    </div>
                `;
            }
        }
        
        // Basculer en sandbox
        async function switchToSandbox() {
            if (!confirm('Passer en mode sandbox? Cette action va modifier votre configuration.')) {
                return;
            }
            
            try {
                const response = await fetch('/switch-mode?mode=sandbox');
                const data = await response.json();
                
                if (data.success) {
                    alert('Mode changé en sandbox! Rechargez la page.');
                    location.reload();
                } else {
                    alert('Erreur: ' + (data.error || 'Inconnue'));
                }
            } catch (error) {
                alert('Erreur: ' + error.message);
            }
        }
        
        // Charger au démarrage
        document.addEventListener('DOMContentLoaded', loadCurrentConfig);
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>