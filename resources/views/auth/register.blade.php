<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Plateforme de Paiement</title>
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
            --info-color: #3498db;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
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
            animation: gradientShift 10s ease infinite alternate;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        .container {
            max-width: 500px;
            width: 100%;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
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
            position: relative;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            animation: progressBar 2s ease-in-out infinite;
        }

        @keyframes progressBar {
            0%, 100% { width: 0%; left: 0; }
            50% { width: 100%; left: 0; }
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            padding: 40px 30px;
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
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 28px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            color: var(--text-color);
            opacity: 0.9;
        }

        .form {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            animation: slideUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .label {
            display: flex;
            align-items: center;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 10px;
            color: var(--text-color);
        }

        .label i {
            margin-right: 10px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
            transition: var(--transition);
        }

        .input-wrapper {
            position: relative;
            transition: var(--transition);
        }

        input {
            width: 100%;
            padding: 16px 50px 16px 20px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
            background: var(--light-gray);
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(243, 222, 44, 0.1);
            transform: scale(1.02);
        }

        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: var(--transition);
            pointer-events: none;
        }

        input:focus + .input-icon {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .password-toggle {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            cursor: pointer;
            z-index: 2;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.1);
        }

        .error-message {
            color: var(--error-color);
            font-size: 12px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .session-status {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .input-error {
            border-color: var(--error-color) !important;
            animation: errorPulse 0.5s ease;
        }

        @keyframes errorPulse {
            0%, 100% { box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1); }
            50% { box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.3); }
        }

        .password-strength {
            margin-top: 8px;
            font-size: 12px;
        }

        .strength-bar {
            height: 4px;
            background: #eee;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
            position: relative;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: var(--error-color);
            transition: var(--transition);
            border-radius: 2px;
        }

        .strength-fill.weak { width: 33%; background: var(--error-color); }
        .strength-fill.medium { width: 66%; background: var(--warning-color); }
        .strength-fill.strong { width: 100%; background: var(--success-color); }

        .strength-text {
            font-size: 11px;
            margin-top: 3px;
            text-align: right;
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 25px;
            padding: 15px;
            background: rgba(243, 222, 44, 0.05);
            border-radius: 12px;
            animation: slideUp 0.7s ease-out;
        }

        .terms-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
            margin-top: 3px;
            flex-shrink: 0;
        }

        .terms-checkbox label {
            font-size: 13px;
            color: var(--text-color);
            line-height: 1.5;
            cursor: pointer;
            user-select: none;
        }

        .terms-checkbox a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--secondary-color);
            padding: 18px;
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
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(243, 222, 44, 0.3);
        }

        .btn:hover::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.1) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) rotate(30deg); }
            100% { transform: translateX(100%) rotate(30deg); }
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

        .btn-loading::before {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid var(--secondary-color);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        .login-link p {
            color: var(--text-color);
            font-size: 14px;
            margin-bottom: 15px;
        }

        .login-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--secondary-color);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .login-btn:hover {
            background: #333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 14px;
            margin-top: 15px;
            padding: 8px 16px;
            border-radius: 6px;
            transition: var(--transition);
        }

        .back-home:hover {
            background: var(--light-gray);
            transform: translateX(-5px);
        }

        .success-animation {
            display: none;
            text-align: center;
            padding: 20px;
            animation: successPop 0.6s ease-out;
        }

        @keyframes successPop {
            0% { transform: scale(0); opacity: 0; }
            70% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            margin: 0 auto 20px;
            animation: successBounce 1s ease;
        }

        @keyframes successBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                padding: 10px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .form {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .logo-icon {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
            
            input {
                padding: 14px 45px 14px 15px;
            }
        }

        @media (max-width: 360px) {
            .header {
                padding: 25px 15px;
            }
            
            .form {
                padding: 25px 15px;
            }
            
            .btn {
                padding: 16px;
            }
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            }
            
            .card {
                background: #2d3748;
                color: #e2e8f0;
            }
            
            .header {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            }
            
            input {
                background: #4a5568;
                border-color: #718096;
                color: #e2e8f0;
            }
            
            input:focus {
                background: #2d3748;
            }
            
            .label {
                color: #e2e8f0;
            }
            
            .terms-checkbox {
                background: rgba(243, 222, 44, 0.1);
            }
            
            .terms-checkbox label {
                color: #e2e8f0;
            }
            
            .back-home {
                color: #e2e8f0;
            }
            
            .back-home:hover {
                background: #4a5568;
            }
        }

        /* Floating labels animation */
        .floating-label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: var(--transition);
            pointer-events: none;
            background: white;
            padding: 0 5px;
            font-size: 15px;
        }

        .input-wrapper:focus-within .floating-label,
        input:not(:placeholder-shown) + .floating-label {
            top: 0;
            font-size: 12px;
            color: var(--primary-color);
            transform: translateY(-50%);
        }

        .form-group:focus-within .label i {
            transform: rotate(15deg);
            color: var(--primary-dark);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <h1>Créer un Compte</h1>
                <p>Rejoignez notre plateforme sécurisée</p>
            </div>

            <div class="form">
                <!-- Session Status -->
                @if(session('status'))
                    <div class="session-status">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label class="label" for="name">
                            <i class="fas fa-user"></i> Nom complet
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}" 
                                placeholder="Votre nom complet"
                                required 
                                autofocus 
                                autocomplete="name"
                                class="{{ $errors->has('name') ? 'input-error' : '' }}"
                            >
                            <div class="input-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                        </div>
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label class="label" for="email">
                            <i class="fas fa-envelope"></i> Adresse Email
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                placeholder="votre@email.com"
                                required 
                                autocomplete="email"
                                class="{{ $errors->has('email') ? 'input-error' : '' }}"
                            >
                            <div class="input-icon">
                                <i class="fas fa-at"></i>
                            </div>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="label" for="password">
                            <i class="fas fa-lock"></i> Mot de passe
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                placeholder="Créez un mot de passe"
                                required 
                                autocomplete="new-password"
                                class="{{ $errors->has('password') ? 'input-error' : '' }}"
                            >
                            <div class="input-icon">
                                <i class="fas fa-key"></i>
                            </div>
                            <span class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="passwordStrength"></div>
                            </div>
                            <div class="strength-text" id="strengthText">Faible</div>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="label" for="password_confirmation">
                            <i class="fas fa-lock"></i> Confirmer le mot de passe
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                placeholder="Confirmez votre mot de passe"
                                required 
                                autocomplete="new-password"
                            >
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <span class="password-toggle" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        @error('password_confirmation')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            J'accepte les <a href="#" id="termsLink">conditions d'utilisation</a> et la 
                            <a href="#" id="privacyLink">politique de confidentialité</a>. Je comprends que mes données 
                            seront traitées de manière sécurisée.
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">
                            <i class="fas fa-user-plus"></i> Créer mon compte
                        </span>
                    </button>
                </form>

                <!-- Success Animation (hidden by default) -->
                <div class="success-animation" id="successAnimation">
                    <div class="success-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3 style="margin-bottom: 10px; color: var(--success-color);">Inscription réussie !</h3>
                    <p style="color: var(--text-color);">Redirection vers votre espace personnel...</p>
                </div>

                <!-- Login Link -->
                <div class="login-link">
                    <p>Vous avez déjà un compte ?</p>
                    <a href="{{ route('login') }}" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </a>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="{{ route('home') }}" class="back-home">
                            <i class="fas fa-arrow-left"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordStrength = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const termsCheckbox = document.getElementById('terms');
            const successAnimation = document.getElementById('successAnimation');
            
            // État du formulaire
            let isFormValid = false;
            
            // Toggle password visibility
            function setupPasswordToggle(input, toggle) {
                toggle.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.innerHTML = type === 'password' ? 
                        '<i class="fas fa-eye"></i>' : 
                        '<i class="fas fa-eye-slash"></i>';
                    
                    // Animation de l'icône
                    this.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 200);
                });
            }
            
            setupPasswordToggle(passwordInput, togglePassword);
            setupPasswordToggle(confirmPasswordInput, toggleConfirmPassword);
            
            // Check password strength
            function checkPasswordStrength(password) {
                let strength = 0;
                const hasLower = /[a-z]/.test(password);
                const hasUpper = /[A-Z]/.test(password);
                const hasNumbers = /\d/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                const isLongEnough = password.length >= 8;
                
                if (hasLower) strength++;
                if (hasUpper) strength++;
                if (hasNumbers) strength++;
                if (hasSpecial) strength++;
                if (isLongEnough) strength++;
                
                return strength;
            }
            
            function updatePasswordStrength(password) {
                const strength = checkPasswordStrength(password);
                
                // Mettre à jour la barre de force
                passwordStrength.className = 'strength-fill';
                
                if (password.length === 0) {
                    strengthText.textContent = 'Faible';
                    passwordStrength.style.width = '0%';
                    return;
                }
                
                if (strength <= 2) {
                    strengthText.textContent = 'Faible';
                    passwordStrength.classList.add('weak');
                } else if (strength <= 4) {
                    strengthText.textContent = 'Moyen';
                    passwordStrength.classList.add('medium');
                } else {
                    strengthText.textContent = 'Fort';
                    passwordStrength.classList.add('strong');
                }
            }
            
            // Validation en temps réel
            function validateField(fieldName, value) {
                const field = document.getElementById(fieldName);
                const wrapper = field.closest('.form-group');
                let isValid = true;
                
                switch(fieldName) {
                    case 'name':
                        isValid = value.trim().length >= 2;
                        break;
                        
                    case 'email':
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        isValid = emailRegex.test(value);
                        break;
                        
                    case 'password':
                        const strength = checkPasswordStrength(value);
                        isValid = strength >= 3 && value.length >= 8;
                        break;
                        
                    case 'password_confirmation':
                        isValid = value === passwordInput.value && value.length > 0;
                        break;
                }
                
                // Mise à jour visuelle
                if (fieldName !== 'password_confirmation') {
                    if (value) {
                        field.classList.toggle('input-error', !isValid);
                    }
                } else {
                    field.classList.toggle('input-error', !isValid);
                }
                
                return isValid;
            }
            
            function validateForm() {
                const fields = {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    password: passwordInput.value,
                    password_confirmation: confirmPasswordInput.value
                };
                
                let formValid = true;
                
                for (const [fieldName, value] of Object.entries(fields)) {
                    const isValid = validateField(fieldName, value);
                    if (!isValid) formValid = false;
                }
                
                // Vérifier les termes
                if (!termsCheckbox.checked) {
                    formValid = false;
                    termsCheckbox.parentElement.style.animation = 'shake 0.5s ease';
                    setTimeout(() => {
                        termsCheckbox.parentElement.style.animation = '';
                    }, 500);
                }
                
                isFormValid = formValid;
                submitBtn.disabled = !formValid;
                
                return formValid;
            }
            
            // Écouteurs d'événements
            const inputs = ['name', 'email', 'password', 'password_confirmation'];
            inputs.forEach(fieldName => {
                const input = document.getElementById(fieldName);
                input.addEventListener('input', function() {
                    if (fieldName === 'password') {
                        updatePasswordStrength(this.value);
                    }
                    validateField(fieldName, this.value);
                    validateForm();
                });
                
                input.addEventListener('blur', function() {
                    validateField(fieldName, this.value);
                    validateForm();
                });
            });
            
            termsCheckbox.addEventListener('change', validateForm);
            
            passwordInput.addEventListener('input', function() {
                // Vérifier aussi la confirmation si elle a déjà une valeur
                if (confirmPasswordInput.value) {
                    validateField('password_confirmation', confirmPasswordInput.value);
                }
            });
            
            // Effets visuels sur les inputs
            inputs.forEach(fieldName => {
                const input = document.getElementById(fieldName);
                input.addEventListener('focus', function() {
                    const wrapper = this.closest('.input-wrapper');
                    wrapper.style.transform = 'scale(1.02)';
                    wrapper.style.boxShadow = '0 5px 20px rgba(243, 222, 44, 0.1)';
                });
                
                input.addEventListener('blur', function() {
                    const wrapper = this.closest('.input-wrapper');
                    wrapper.style.transform = 'scale(1)';
                    wrapper.style.boxShadow = 'none';
                });
            });
            
            // Gestion de la soumission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!isFormValid) {
                    // Animer les champs erronés
                    const errorInputs = document.querySelectorAll('.input-error');
                    errorInputs.forEach(input => {
                        input.style.animation = 'errorPulse 0.5s ease';
                        setTimeout(() => {
                            input.style.animation = '';
                        }, 500);
                    });
                    return;
                }
                
                // Animation de chargement
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                
                // Simuler un délai de traitement
                setTimeout(() => {
                    // Cacher le formulaire
                    form.style.opacity = '0';
                    form.style.transform = 'translateY(-20px)';
                    form.style.height = '0';
                    form.style.overflow = 'hidden';
                    
                    // Afficher l'animation de succès
                    successAnimation.style.display = 'block';
                    
                    // Soumettre le formulaire après l'animation
                    setTimeout(() => {
                        form.submit();
                    }, 2000);
                }, 1500);
            });
            
            // Modal pour les conditions (simplifié)
            const termsLink = document.getElementById('termsLink');
            const privacyLink = document.getElementById('privacyLink');
            
            [termsLink, privacyLink].forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const modal = document.createElement('div');
                    modal.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.8);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        z-index: 10000;
                        animation: fadeIn 0.3s ease;
                    `;
                    
                    const modalContent = document.createElement('div');
                    modalContent.style.cssText = `
                        background: white;
                        padding: 30px;
                        border-radius: 15px;
                        max-width: 500px;
                        width: 90%;
                        max-height: 80vh;
                        overflow-y: auto;
                        animation: slideUp 0.5s ease;
                    `;
                    
                    modalContent.innerHTML = `
                        <h3 style="margin-bottom: 15px; color: var(--primary-color);">
                            ${this === termsLink ? 'Conditions d\'utilisation' : 'Politique de confidentialité'}
                        </h3>
                        <p style="margin-bottom: 20px; line-height: 1.6;">
                            ${this === termsLink ? 
                                'En créant un compte, vous acceptez nos conditions d\'utilisation...' :
                                'Nous protégeons vos données personnelles conformément au RGPD...'}
                        </p>
                        <button onclick="this.closest('.modal-overlay').remove()" 
                                style="padding: 10px 20px; background: var(--primary-color); color: var(--secondary-color); border: none; border-radius: 8px; cursor: pointer;">
                            J\'ai compris
                        </button>
                    `;
                    
                    modal.className = 'modal-overlay';
                    modal.appendChild(modalContent);
                    document.body.appendChild(modal);
                    
                    // Fermer en cliquant à l'extérieur
                    modal.addEventListener('click', function(event) {
                        if (event.target === this) {
                            this.remove();
                        }
                    });
                });
            });
            
            // Animation du logo au survol
            const logoIcon = document.querySelector('.logo-icon');
            logoIcon.addEventListener('mouseenter', function() {
                this.style.transform = 'rotate(360deg) scale(1.1)';
                this.style.transition = 'transform 0.5s ease';
            });
            
            logoIcon.addEventListener('mouseleave', function() {
                this.style.transform = 'rotate(0deg) scale(1)';
            });
            
            // Validation initiale
            validateForm();
            
            // Ajouter des styles pour les modaux
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                .modal-overlay {
                    animation: fadeIn 0.3s ease !important;
                }
                
                .modal-overlay > div {
                    animation: slideUp 0.5s ease !important;
                }
                
                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(50px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>
</html>