<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement en ligne</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #F3DE2C;
            --primary-dark: #e2cd24;
            --secondary-color: #111;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .header {
            background: var(--primary-color);
            padding: 30px 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 8px;
            position: relative;
        }

        .header h1 i {
            margin-right: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .header p {
            font-size: 14px;
            color: var(--text-color);
            opacity: 0.9;
        }

        .form {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .label {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--text-color);
        }

        .label i {
            margin-right: 8px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }

        .input-wrapper {
            position: relative;
        }

        input, textarea {
            width: 100%;
            padding: 14px 45px 14px 15px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
            background: var(--light-gray);
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(243, 222, 44, 0.1);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .phone-input {
            display: flex;
            align-items: center;
            background: var(--light-gray);
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid var(--border-color);
            transition: var(--transition);
        }

        .phone-input:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(243, 222, 44, 0.1);
        }

        .phone-prefix {
            background: var(--primary-color);
            color: var(--secondary-color);
            font-weight: 600;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 80px;
        }

        .phone-input input {
            border: none;
            background: transparent;
            padding: 14px 15px;
            flex: 1;
        }

        .phone-input input:focus {
            box-shadow: none;
            background: transparent;
        }

        .error-message {
            color: var(--error-color);
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-message i {
            margin-right: 5px;
        }

        .input-error {
            border-color: var(--error-color) !important;
        }

        .input-success {
            border-color: var(--success-color) !important;
        }

        .validation-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0;
            transition: var(--transition);
        }

        .valid .validation-icon {
            opacity: 1;
            color: var(--success-color);
        }

        .invalid .validation-icon {
            opacity: 1;
            color: var(--error-color);
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 480px) {
            .row {
                grid-template-columns: 1fr;
            }
        }

        .btn {
            width: 100%;
            background: var(--primary-color);
            color: var(--secondary-color);
            padding: 16px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-loading {
            position: relative;
        }

        .btn-loading .btn-text {
            opacity: 0;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid var(--secondary-color);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .secure-info {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        .secure {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--text-color);
            font-size: 14px;
            margin-bottom: 15px;
        }

        .secure i {
            color: var(--success-color);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
            padding: 8px 16px;
            border-radius: 8px;
        }

        .back-link:hover {
            background: var(--light-gray);
            color: var(--secondary-color);
        }

        .server-error {
            background: #fee;
            border: 2px solid var(--error-color);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 25px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .server-error-content {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .server-error i {
            color: var(--error-color);
            font-size: 18px;
            margin-top: 2px;
        }

        .server-error p {
            color: var(--error-color);
            font-size: 14px;
            margin: 0;
            flex: 1;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="card">
        <div class="header">
            <h1><i class="fas fa-lock"></i> Paiement Sécurisé</h1>
            <p>Remplissez le formulaire pour procéder au paiement</p>
        </div>

        <form id="paymentForm" action="{{ route('payment.process') }}" method="POST" class="form">
            @csrf

            <!-- Erreurs serveur -->
            @if(session('error'))
                <div class="server-error">
                    <div class="server-error-content">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->has('fedapay_error'))
                <div class="server-error">
                    <div class="server-error-content">
                        <i class="fas fa-exclamation-circle"></i>
                        <p>{{ $errors->first('fedapay_error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Montant -->
            <div class="form-group">
                <label class="label"><i class="fas fa-money-bill-wave"></i> Montant (FCFA)</label>
                <div class="input-wrapper">
                    <input type="number" 
                           name="amount" 
                           id="amount"
                           value="{{ old('amount', 5000) }}"
                           min="100" 
                           max="10000000"
                           step="100"
                           placeholder="5000"
                           required>
                    <div class="validation-icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="error-message" id="amount-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span></span>
                </div>
            </div>

            <!-- Prénom et Nom -->
            <div class="row">
                <div class="form-group">
                    <label class="label"><i class="fas fa-user"></i> Prénom</label>
                    <div class="input-wrapper">
                        <input type="text" 
                               name="firstname" 
                               id="firstname"
                               value="{{ old('firstname') }}"
                               placeholder="Votre prénom"
                               required
                               minlength="2"
                               maxlength="50">
                        <div class="validation-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <div class="error-message" id="firstname-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label"><i class="fas fa-user"></i> Nom</label>
                    <div class="input-wrapper">
                        <input type="text" 
                               name="lastname" 
                               id="lastname"
                               value="{{ old('lastname') }}"
                               placeholder="Votre nom"
                               required
                               minlength="2"
                               maxlength="50">
                        <div class="validation-icon">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                    <div class="error-message" id="lastname-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span></span>
                    </div>
                </div>
            </div>

            <!-- Téléphone -->
            <div class="form-group">
                <label class="label"><i class="fas fa-phone"></i> Téléphone</label>
                <div class="phone-input">
                    <div class="phone-prefix">+229</div>
                    <input type="text" 
                           name="phone" 
                           id="phone"
                           value="{{ old('phone') }}"
                           placeholder="0190000000"
                           required
                           pattern="0\d{9}"
                           maxlength="10">
                </div>
                <div class="error-message" id="phone-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Format: 0190000000 (10 chiffres commençant par 0)</span>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label class="label"><i class="fas fa-envelope"></i> Email</label>
                <div class="input-wrapper">
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email') }}"
                           placeholder="votre@email.com"
                           required>
                    <div class="validation-icon">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="error-message" id="email-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span></span>
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label class="label"><i class="fas fa-file-alt"></i> Description (optionnel)</label>
                <textarea name="description" 
                          id="description"
                          placeholder="Objet du paiement...">{{ old('description') }}</textarea>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn" id="submitBtn" disabled>
                <span class="btn-text">
                    <i class="fas fa-credit-card"></i> Procéder au paiement
                </span>
            </button>

            <!-- Informations de sécurité -->
            <div class="secure-info">
                <p class="secure">
                    <i class="fas fa-shield-alt"></i>
                    Paiement 100% sécurisé par FedaPay
                </p>
                <a href="{{ route('home') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i> Retourner à l'accueil
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitBtn');
        const inputs = {
            amount: document.getElementById('amount'),
            firstname: document.getElementById('firstname'),
            lastname: document.getElementById('lastname'),
            phone: document.getElementById('phone'),
            email: document.getElementById('email'),
            description: document.getElementById('description')
        };

        const errors = {
            amount: document.getElementById('amount-error'),
            firstname: document.getElementById('firstname-error'),
            lastname: document.getElementById('lastname-error'),
            phone: document.getElementById('phone-error'),
            email: document.getElementById('email-error')
        };

        let isFormValid = false;

        // Validation en temps réel
        function validateField(fieldName, value) {
            const field = inputs[fieldName];
            const error = errors[fieldName];
            const wrapper = field.closest('.input-wrapper') || field.closest('.phone-input');
            
            let isValid = true;
            let message = '';

            switch(fieldName) {
                case 'amount':
                    const amount = parseInt(value);
                    if (!value || isNaN(amount) || amount < 100 || amount > 10000000) {
                        isValid = false;
                        message = 'Le montant doit être entre 100 et 10.000.000 FCFA';
                    }
                    break;
                    
                case 'firstname':
                case 'lastname':
                    if (!value || value.trim().length < 2) {
                        isValid = false;
                        message = 'Minimum 2 caractères';
                    } else if (value.length > 50) {
                        isValid = false;
                        message = 'Maximum 50 caractères';
                    }
                    break;
                    
                case 'phone':
                    const phoneRegex = /^0\d{9}$/;
                    if (!value) {
                        isValid = false;
                        message = 'Le téléphone est requis';
                    } else if (!phoneRegex.test(value)) {
                        isValid = false;
                        message = 'Format: 0XXXXXXXXX (10 chiffres)';
                    }
                    break;
                    
                case 'email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!value) {
                        isValid = false;
                        message = 'L\'email est requis';
                    } else if (!emailRegex.test(value)) {
                        isValid = false;
                        message = 'Format email invalide';
                    }
                    break;
            }

            // Mise à jour de l'état visuel
            if (wrapper) {
                wrapper.classList.remove('valid', 'invalid');
                if (value) {
                    wrapper.classList.add(isValid ? 'valid' : 'invalid');
                }
            }

            // Affichage du message d'erreur
            if (error) {
                error.style.display = isValid ? 'none' : 'flex';
                error.querySelector('span').textContent = message;
            }

            return isValid;
        }

        // Validation du formulaire complet
        function validateForm() {
            let formValid = true;
            
            for (const fieldName in inputs) {
                if (fieldName !== 'description') { // Description est optionnel
                    const isValid = validateField(fieldName, inputs[fieldName].value);
                    if (!isValid) formValid = false;
                }
            }
            
            isFormValid = formValid;
            submitBtn.disabled = !formValid;
        }

        // Événements d'input
        for (const fieldName in inputs) {
            inputs[fieldName].addEventListener('input', function(e) {
                // Formatage automatique du téléphone
                if (fieldName === 'phone') {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0 && !value.startsWith('0')) {
                        value = '0' + value;
                    }
                    if (value.length > 10) {
                        value = value.substring(0, 10);
                    }
                    e.target.value = value;
                }
                
                validateField(fieldName, e.target.value);
                validateForm();
            });

            inputs[fieldName].addEventListener('blur', function(e) {
                validateField(fieldName, e.target.value);
                validateForm();
            });
        }

        // Validation initiale
        validateForm();

        // Soumission du formulaire
        form.addEventListener('submit', function(e) {
            if (!isFormValid) {
                e.preventDefault();
                return;
            }

            // Animation de chargement
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
            
            // Validation finale avant envoi
            let finalValidation = true;
            for (const fieldName in inputs) {
                if (fieldName !== 'description') {
                    if (!validateField(fieldName, inputs[fieldName].value)) {
                        finalValidation = false;
                    }
                }
            }

            if (!finalValidation) {
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
                e.preventDefault();
                
                // Scroll vers la première erreur
                const firstError = document.querySelector('.invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Auto-remplissage du téléphone si déjà dans l'URL
        const urlParams = new URLSearchParams(window.location.search);
        const phoneParam = urlParams.get('phone');
        if (phoneParam) {
            inputs.phone.value = phoneParam;
            validateField('phone', phoneParam);
            validateForm();
        }

        // Formatage du montant à la saisie
        inputs.amount.addEventListener('change', function(e) {
            let value = parseInt(e.target.value);
            if (value < 100) value = 100;
            if (value > 10000000) value = 10000000;
            e.target.value = Math.floor(value / 100) * 100; // Arrondir à la centaine
            validateField('amount', e.target.value);
            validateForm();
        });
    });
</script>

</body>
</html>