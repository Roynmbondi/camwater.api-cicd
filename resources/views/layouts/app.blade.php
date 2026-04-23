<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CamWater - Gestion de l\'eau')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-tint text-2xl"></i>
                    <span class="text-xl font-bold">CamWater</span>
                </div>
                
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-200 transition">
                        <i class="fas fa-home mr-2"></i>Tableau de bord
                    </a>
                    <a href="{{ route('abonnes.index') }}" class="hover:text-blue-200 transition">
                        <i class="fas fa-users mr-2"></i>Abonnés
                    </a>
                    <a href="{{ route('factures.index') }}" class="hover:text-blue-200 transition">
                        <i class="fas fa-file-invoice mr-2"></i>Factures
                    </a>
                    <a href="{{ route('reclamations.index') }}" class="hover:text-blue-200 transition">
                        <i class="fas fa-exclamation-circle mr-2"></i>Réclamations
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-sm" id="user-name">Utilisateur</span>
                    <button onclick="logout()" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12 py-6">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} CamWater. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- API Configuration -->
    <script>
        const API_URL = '{{ env('APP_URL') }}/api';
        const token = localStorage.getItem('auth_token');
        
        // Configure axios defaults
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        }
        
        // Logout function
        function logout() {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.href = '{{ route('login') }}';
        }
        
        // Check if user is logged in
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        if (user.name) {
            document.getElementById('user-name').textContent = user.name;
        }
    </script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    @stack('scripts')
</body>
</html>
