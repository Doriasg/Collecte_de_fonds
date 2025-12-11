<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Plateforme de Paiement</title>
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
        }

        .container {
            max-width: 450px;
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
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .header {
            background: var(--primary-color);
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
            width: 50px;
            height: 50px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 24px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
        }

        .input-wrapper {
            position: relative;
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
        }

        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: var(--transition);
        }

        input:focus + .input-icon {
            color: var(--primary-color);
        }

        .error-message {
            color: var(--error-color);
            font-size: 12px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .session-status i {
            font-size: 18px;
        }

        .input-error {
            border-color: var(--error-color) !important;
        }

        .input-error:focus {
            box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            cursor: pointer;
        }

        .remember-me label {
            font-size: 14px;
            color: var(--text-color);
            cursor: pointer;
            user-select: none;
        }

        .forgot-password {
            font-size: 14px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn {
            width: 100%;
            background: var(--primary-color);
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
        }

        .btn:hover {
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

        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
        }

        .register-link p {
            color: var(--text-color);
            font-size: 14px;
            margin-bottom: 15px;
        }

        .register-btn {
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

        .register-btn:hover {
            background: #333;
            transform: translateY(-2px);
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
            
            .remember-forgot {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
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

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            }
            
            .card {
                background: #2d3748;
                color: #e2e8f0;
            }
            
            .header {
                background: var(--primary-color);
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
            
            .remember-me label {
                color: #e2e8f0;
            }
            
            .back-home {
                color: #e2e8f0;
            }
            
            .back-home:hover {
                background: #4a5568;
            }
        }

        /* Animations supplémentaires */
        .form-group {
            animation: slideUp 0.5s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .remember-forgot { animation-delay: 0.3s; }
        .btn { animation-delay: 0.4s; }
        .register-link { animation-delay: 0.5s; }

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

        /* Loader pour le bouton */
        .loader {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
        }

        .loading .loader {
            display: block;
        }

        .loading .btn-text {
            opacity: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <h1>Connexion Sécurisée</h1>
                <p>Accédez à votre espace personnel</p>
            </div>

            <div class="form">
                <!-- Session Status -->
                @if(session('status'))
                    <div class="session-status">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

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
                                autofocus 
                                autocomplete="email"
                                class="{{ $errors->has('email') ? 'input-error' : '' }}"
                            >
                            <div class="input-icon">
                                <i class="fas fa-user-circle"></i>
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
                                placeholder="Votre mot de passe"
                                required 
                                autocomplete="current-password"
                                class="{{ $errors->has('password') ? 'input-error' : '' }}"
                            >
                            <div class="input-icon">
                                <i class="fas fa-key"></i>
                            </div>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="remember_me" name="remember">
                            <label for="remember_me">Se souvenir de moi</label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a class="forgot-password" href="{{ route('password.request') }}">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn" id="submitBtn">
                        <span class="btn-text">
                            <i class="fas fa-sign-in-alt"></i> Se connecter
                        </span>
                        <div class="loader"></div>
                    </button>
                </form>

                <!-- Register Link -->
                <div class="register-link">
                    <p>Vous n'avez pas de compte ?</p>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="register-btn">
                            <i class="fas fa-user-plus"></i> Créer un compte
                        </a>
                    @endif
                    
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
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            // Validation en temps réel
            function validateEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
            
            function validatePassword(password) {
                return password.length >= 8;
            }
            
            function updateButtonState() {
                const emailValid = validateEmail(emailInput.value);
                const passwordValid = validatePassword(passwordInput.value);
                submitBtn.disabled = !(emailValid && passwordValid);
            }
            
            // Écouteurs d'événements
            emailInput.addEventListener('input', updateButtonState);
            passwordInput.addEventListener('input', updateButtonState);
            
            // Validation initiale
            updateButtonState();
            
            // Gestion de la soumission
            form.addEventListener('submit', function(e) {
                if (!validateEmail(emailInput.value)) {
                    e.preventDefault();
                    showError(emailInput, 'Veuillez entrer une adresse email valide');
                    return;
                }
                
                if (!validatePassword(passwordInput.value)) {
                    e.preventDefault();
                    showError(passwordInput, 'Le mot de passe doit contenir au moins 8 caractères');
                    return;
                }
                
                // Animation de chargement
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
            
            function showError(input, message) {
                // Créer ou mettre à jour le message d'erreur
                let errorDiv = input.parentElement.nextElementSibling;
                if (!errorDiv || !errorDiv.classList.contains('error-message')) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i><span>${message}</span>`;
                    input.parentElement.parentElement.appendChild(errorDiv);
                } else {
                    errorDiv.querySelector('span').textContent = message;
                    errorDiv.style.display = 'flex';
                }
                
                // Ajouter la classe d'erreur
                input.classList.add('input-error');
                
                // Focus sur le champ erroné
                input.focus();
                
                // Supprimer l'erreur lors de la nouvelle saisie
                input.addEventListener('input', function() {
                    input.classList.remove('input-error');
                    if (errorDiv && errorDiv.parentElement) {
                        errorDiv.style.display = 'none';
                    }
                }, { once: true });
            }
            
            // Toggle password visibility (optionnel)
            const togglePassword = document.createElement('span');
            togglePassword.className = 'password-toggle';
            togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            togglePassword.style.cssText = `
                position: absolute;
                right: 20px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: #999;
                z-index: 2;
            `;
            
            // Insérer après l'icône du mot de passe
            const passwordWrapper = passwordInput.parentElement;
            passwordWrapper.appendChild(togglePassword);
            
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // Effets visuels supplémentaires
            const inputs = [emailInput, passwordInput];
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
            
            // Auto-remplissage amélioré
            const savedEmail = localStorage.getItem('rememberedEmail');
            if (savedEmail && emailInput.value === '') {
                emailInput.value = savedEmail;
                const rememberCheckbox = document.getElementById('remember_me');
                if (rememberCheckbox) {
                    rememberCheckbox.checked = true;
                }
                updateButtonState();
            }
            
            // Sauvegarder l'email si "Se souvenir de moi" est coché
            form.addEventListener('submit', function() {
                const rememberCheckbox = document.getElementById('remember_me');
                if (rememberCheckbox && rememberCheckbox.checked && emailInput.value) {
                    localStorage.setItem('rememberedEmail', emailInput.value);
                } else {
                    localStorage.removeItem('rememberedEmail');
                }
            });
        });
    </script>
</body>
</html>