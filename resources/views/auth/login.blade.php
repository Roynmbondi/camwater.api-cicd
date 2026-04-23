<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - CamWater</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-500 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <i class="fas fa-tint text-6xl text-blue-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">CamWater</h1>
            <p class="text-gray-600 mt-2">Gestion de distribution d'eau</p>
        </div>

        <form id="loginForm" class="space-y-6">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-envelope mr-2"></i>Email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="votre@email.com"
                >
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-lock mr-2"></i>Mot de passe
                </label>
                <input 
                    type="password" 
                    id="password" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="••••••••"
                >
            </div>

            <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span id="error-text"></span>
            </div>

            <button 
                type="submit" 
                id="loginBtn"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200 flex items-center justify-center"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>
                <span id="btnText">Se connecter</span>
            </button>
        </form>

        <div class="mt-6 text-center text-gray-600 text-sm">
            <p>Compte de test :</p>
            <p class="font-mono bg-gray-100 p-2 rounded mt-2">
                admin@camwater.com / password
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const API_URL = '{{ env('APP_URL') }}/api';
        
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            const loginBtn = document.getElementById('loginBtn');
            const btnText = document.getElementById('btnText');
            
            // Hide error
            errorDiv.classList.add('hidden');
            
            // Show loading
            loginBtn.disabled = true;
            btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Connexion...';
            
            try {
                const response = await axios.post(`${API_URL}/auth/login`, {
                    email,
                    password
                });
                
                // Save token and user
                localStorage.setItem('auth_token', response.data.access_token);
                localStorage.setItem('user', JSON.stringify(response.data.user));
                
                // Redirect to dashboard
                window.location.href = '{{ route('dashboard') }}';
                
            } catch (error) {
                // Show error
                errorDiv.classList.remove('hidden');
                errorText.textContent = error.response?.data?.message || 'Erreur de connexion';
                
                // Reset button
                loginBtn.disabled = false;
                btnText.innerHTML = 'Se connecter';
            }
        });
    </script>
</body>
</html>
