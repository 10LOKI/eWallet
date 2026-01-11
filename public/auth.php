<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetManager | Authentification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="../test.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="fixed inset-0 bg-gradient-primary opacity-10"></div>
    
    <div class="relative w-full max-w-4xl flex flex-col md:flex-row rounded-2xl overflow-hidden card-shadow bg-white">
        
        <div class="md:w-1/2 bg-gradient-primary text-white p-8 md:p-12 flex flex-col justify-center">
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold mb-2">BudgetManager</h1>
                <p class="text-blue-100">Gérez vos finances intelligemment</p>
            </div>
            
            <div class="space-y-6 mb-10">
                <div class="flex items-start">
                    <div class="bg-white/20 p-2 rounded-lg mr-4">
                        <i class="fas fa-wallet text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Wallet mensuel</h3>
                        <p class="text-blue-100">Définissez et suivez votre budget chaque mois</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-white/20 p-2 rounded-lg mr-4">
                        <i class="fas fa-chart-pie text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Statistiques détaillées</h3>
                        <p class="text-blue-100">Analysez vos dépenses par catégories</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-white/20 p-2 rounded-lg mr-4">
                        <i class="fas fa-sync-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">Dépenses automatiques</h3>
                        <p class="text-blue-100">Programmez vos dépenses récurrentes</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-auto">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-3">
                        <i class="fas fa-quote-left"></i>
                    </div>
                    <div>
                        <p class="italic">"Avec BudgetManager, j'ai enfin pris le contrôle de mes finances."</p>
                        <p class="text-sm text-blue-100 mt-1">- Marie, utilisatrice depuis 6 mois</p>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="md:w-1/2 p-8 md:p-12">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Bienvenue</h2>
                <p class="text-gray-600">Connectez-vous ou créez un compte pour gérer votre budget</p>
            </div>
            
            
            <div class="flex mb-8 bg-gray-100 p-1 rounded-xl">
                <button id="login-tab" class="tab-active flex-1 py-3 px-4 rounded-xl transition-all-300">
                    Connexion
                </button>
                <button id="register-tab" class="flex-1 py-3 px-4 rounded-xl text-gray-600 transition-all-300 hover:text-gray-800">
                    Inscription
                </button>
            </div>
            
            
            <form id="login-form" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                id="login-email" 
                                name="email" 
                                required 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all-300"
                                placeholder="votre@email.com"
                            >
                        </div>
                        <div id="login-email-error" class="error-message hidden"></div>
                    </div>
                    
                    <div>
                        <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="login-password" 
                                name="password" 
                                required 
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all-300"
                                placeholder="Votre mot de passe"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="toggle-login-password">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        <div id="login-password-error" class="error-message hidden"></div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="remember-me" 
                                name="remember-me" 
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            >
                            <label for="remember-me" class="ml-2 block text-sm text-gray-700">
                                Se souvenir de moi
                            </label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Mot de passe oublié?
                        </a>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-gradient-primary text-white font-semibold py-3 px-4 rounded-xl hover:opacity-90 transition-all-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                </button>
                
                <div id="login-message" class="hidden"></div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Vous n'avez pas de compte? 
                        <a href="#" id="switch-to-register" class="text-blue-600 hover:text-blue-800 font-medium">S'inscrire</a>
                    </p>
                </div>
            </form>
            
            
            <form id="register-form" class="space-y-6 hidden">
                <div class="space-y-4">
                    <div>
                        <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="register-name" 
                                name="name" 
                                required 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all-300"
                                placeholder="Votre nom complet"
                            >
                        </div>
                        <div id="register-name-error" class="error-message hidden"></div>
                    </div>
                    
                    <div>
                        <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                id="register-email" 
                                name="email" 
                                required 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all-300"
                                placeholder="votre@email.com"
                            >
                        </div>
                        <div id="register-email-error" class="error-message hidden"></div>
                    </div>
                    
                    <div>
                        <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="register-password" 
                                name="password" 
                                required 
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all-300"
                                placeholder="Créez un mot de passe sécurisé"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="toggle-register-password">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        <div id="register-password-error" class="error-message hidden"></div>
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 caractères avec chiffres et lettres</p>
                    </div>
                    
                    <div>
                        <label for="register-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                id="register-confirm-password" 
                                name="confirm-password" 
                                required 
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all-300"
                                placeholder="Répétez votre mot de passe"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="toggle-register-confirm-password">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                        <div id="register-confirm-password-error" class="error-message hidden"></div>
                    </div>
                    
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="accept-terms" 
                            name="accept-terms" 
                            required 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="accept-terms" class="ml-2 block text-sm text-gray-700">
                            J'accepte les <a href="#" class="text-blue-600 hover:text-blue-800">conditions d'utilisation</a> et la <a href="#" class="text-blue-600 hover:text-blue-800">politique de confidentialité</a>
                        </label>
                    </div>
                    <div id="register-terms-error" class="error-message hidden"></div>
                </div>
                
                <button type="submit" class="w-full bg-gradient-secondary text-white font-semibold py-3 px-4 rounded-xl hover:opacity-90 transition-all-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-user-plus mr-2"></i> Créer mon compte
                </button>
                
                <div id="register-message" class="hidden"></div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Vous avez déjà un compte? 
                        <a href="#" id="switch-to-login" class="text-blue-600 hover:text-blue-800 font-medium">Se connecter</a>
                    </p>
                </div>
            </form>
            
            
            <div class="my-8 flex items-center">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="flex-shrink mx-4 text-gray-500 text-sm">Ou continuer avec</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>
            
            
            <div class="grid grid-cols-2 gap-3">
                <button class="flex items-center justify-center py-3 px-4 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all-300">
                    <i class="fab fa-google text-red-500 mr-2"></i>
                    <span class="text-sm font-medium">Google</span>
                </button>
                <button class="flex items-center justify-center py-3 px-4 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all-300">
                    <i class="fab fa-github text-gray-800 mr-2"></i>
                    <span class="text-sm font-medium">GitHub</span>
                </button>
            </div>
        </div>
    </div>
    
    <script>
        
        const loginTab = document.getElementById('login-tab');
        const registerTab = document.getElementById('register-tab');
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const switchToRegister = document.getElementById('switch-to-register');
        const switchToLogin = document.getElementById('switch-to-login');
        
        
        function activateLoginTab() {
            loginTab.classList.add('tab-active');
            loginTab.classList.remove('text-gray-600');
            loginForm.classList.remove('hidden');
            
            registerTab.classList.remove('tab-active');
            registerTab.classList.add('text-gray-600');
            registerForm.classList.add('hidden');
        }
        
        
        function activateRegisterTab() {
            registerTab.classList.add('tab-active');
            registerTab.classList.remove('text-gray-600');
            registerForm.classList.remove('hidden');
            
            loginTab.classList.remove('tab-active');
            loginTab.classList.add('text-gray-600');
            loginForm.classList.add('hidden');
        }
        
        
        loginTab.addEventListener('click', activateLoginTab);
        registerTab.addEventListener('click', activateRegisterTab);
        switchToRegister.addEventListener('click', function(e) {
            e.preventDefault();
            activateRegisterTab();
        });
        switchToLogin.addEventListener('click', function(e) {
            e.preventDefault();
            activateLoginTab();
        });
        
        
        const toggleLoginPassword = document.getElementById('toggle-login-password');
        const loginPassword = document.getElementById('login-password');
        const toggleRegisterPassword = document.getElementById('toggle-register-password');
        const registerPassword = document.getElementById('register-password');
        const toggleRegisterConfirmPassword = document.getElementById('toggle-register-confirm-password');
        const registerConfirmPassword = document.getElementById('register-confirm-password');
        
        toggleLoginPassword.addEventListener('click', function() {
            const type = loginPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            loginPassword.setAttribute('type', type);
            toggleLoginPassword.querySelector('i').classList.toggle('fa-eye');
            toggleLoginPassword.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        toggleRegisterPassword.addEventListener('click', function() {
            const type = registerPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            registerPassword.setAttribute('type', type);
            toggleRegisterPassword.querySelector('i').classList.toggle('fa-eye');
            toggleRegisterPassword.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        toggleRegisterConfirmPassword.addEventListener('click', function() {
            const type = registerConfirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            registerConfirmPassword.setAttribute('type', type);
            toggleRegisterConfirmPassword.querySelector('i').classList.toggle('fa-eye');
            toggleRegisterConfirmPassword.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        
        function showError(inputId, message) {
            const errorElement = document.getElementById(inputId + '-error');
            const inputElement = document.getElementById(inputId);
            
            if (errorElement && inputElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                errorElement.classList.add('error-message');
                inputElement.classList.add('input-error');
            }
        }
        
        
        function clearError(inputId) {
            const errorElement = document.getElementById(inputId + '-error');
            const inputElement = document.getElementById(inputId);
            
            if (errorElement && inputElement) {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
                inputElement.classList.remove('input-error');
            }
        }
        
        
        function showMessage(formType, message, isSuccess = false) {
            const messageElement = document.getElementById(formType + '-message');
            if (messageElement) {
                messageElement.textContent = message;
                messageElement.classList.remove('hidden');
                messageElement.classList.remove('success-message', 'error-message');
                messageElement.classList.add(isSuccess ? 'success-message' : 'error-message');
                
                
                setTimeout(() => {
                    messageElement.classList.add('hidden');
                }, 5000);
            }
        }
        
        
        function validateRegisterForm() {
            let isValid = true;
            
            
            const name = document.getElementById('register-name').value;
            if (name.trim().length < 2) {
                showError('register-name', 'Le nom doit contenir au moins 2 caractères');
                isValid = false;
            } else {
                clearError('register-name');
            }
            
            
            const email = document.getElementById('register-email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('register-email', 'Veuillez entrer une adresse email valide');
                isValid = false;
            } else {
                clearError('register-email');
            }
            
            
            const password = document.getElementById('register-password').value;
            if (password.length < 8) {
                showError('register-password', 'Le mot de passe doit contenir au moins 8 caractères');
                isValid = false;
            } else if (!/\d/.test(password) || !/[a-zA-Z]/.test(password)) {
                showError('register-password', 'Le mot de passe doit contenir des lettres et des chiffres');
                isValid = false;
            } else {
                clearError('register-password');
            }
            
            
            const confirmPassword = document.getElementById('register-confirm-password').value;
            if (password !== confirmPassword) {
                showError('register-confirm-password', 'Les mots de passe ne correspondent pas');
                isValid = false;
            } else {
                clearError('register-confirm-password');
            }
            
            
            const acceptTerms = document.getElementById('accept-terms').checked;
            if (!acceptTerms) {
                showError('register-terms', 'Vous devez accepter les conditions d\'utilisation');
                isValid = false;
            } else {
                clearError('register-terms');
            }
            
            return isValid;
        }
        
        
        function validateLoginForm() {
            let isValid = true;
            
            
            const email = document.getElementById('login-email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('login-email', 'Veuillez entrer une adresse email valide');
                isValid = false;
            } else {
                clearError('login-email');
            }
            
            
            const password = document.getElementById('login-password').value;
            if (password.length === 0) {
                showError('login-password', 'Veuillez entrer votre mot de passe');
                isValid = false;
            } else {
                clearError('login-password');
            }
            
            return isValid;
        }
        
        
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateRegisterForm()) {
                showMessage('register', 'Création de votre compte en cours...', true);
                
                const formData = new FormData(registerForm);
                formData.append('action', 'register');
                
                fetch('../src/authHandler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('register', data.message, true);
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 2000);
                    } else {
                        showMessage('register', data.message, false);
                    }
                })
                .catch(error => {
                    showMessage('register', 'Erreur de communication avec le serveur', false);
                    console.error('Error:', error);
                });
            }
        });
        
        
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateLoginForm()) {
                showMessage('login', 'Connexion en cours...', true);
                
                const formData = new FormData(loginForm);
                formData.append('action', 'login');
                
                fetch('../src/authHandler.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('login', data.message, true);
                        setTimeout(() => {
                            window.location.href = data.redirect || 'index.php';
                        }, 1500);
                    } else {
                        showMessage('login', data.message, false);
                    }
                })
                .catch(error => {
                    showMessage('login', 'Erreur de communication avec le serveur', false);
                    console.error('Error:', error);
                });
            }
        });
        
        
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', function() {
                clearError(this.id);
            });
        });
    </script>
</body>
</html>