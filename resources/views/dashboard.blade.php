@extends('layouts.app')

@section('title', 'Tableau de bord - CamWater')

@section('content')
<div x-data="dashboardData()" x-init="loadData()">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-line mr-3"></i>Tableau de bord
        </h1>
        <p class="text-gray-600 mt-2">Vue d'ensemble de votre système de gestion</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Abonnés -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Total Abonnés</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" x-text="stats.total_abonnes">0</p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-users text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Factures Impayées -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Factures Impayées</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" x-text="stats.factures_impayees">0</p>
                </div>
                <div class="bg-red-100 rounded-full p-4">
                    <i class="fas fa-file-invoice text-red-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Réclamations -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Réclamations</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" x-text="stats.reclamations">0</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-4">
                    <i class="fas fa-exclamation-circle text-yellow-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Revenus du mois -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-semibold">Revenus du mois</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2" x-text="formatMoney(stats.revenus_mois)">0 FCFA</p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Répartition des abonnés -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-chart-pie mr-2"></i>Répartition des abonnés
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Particuliers</span>
                        <span class="font-semibold" x-text="stats.particuliers">0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-600 h-3 rounded-full" :style="`width: ${stats.particuliers_percent}%`"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Professionnels</span>
                        <span class="font-semibold" x-text="stats.professionnels">0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-600 h-3 rounded-full" :style="`width: ${stats.professionnels_percent}%`"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières activités -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-history mr-2"></i>Dernières activités
            </h3>
            <div class="space-y-3">
                <template x-for="activity in activities" :key="activity.id">
                    <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded">
                        <i class="fas fa-circle text-blue-500 text-xs mt-1"></i>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800" x-text="activity.description"></p>
                            <p class="text-xs text-gray-500 mt-1" x-text="activity.date"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-bolt mr-2"></i>Actions rapides
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('abonnes.create') }}" class="flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-4 rounded-lg transition">
                <i class="fas fa-user-plus"></i>
                <span>Nouvel abonné</span>
            </a>
            <a href="{{ route('factures.index') }}" class="flex items-center justify-center space-x-2 bg-green-600 hover:bg-green-700 text-white px-6 py-4 rounded-lg transition">
                <i class="fas fa-file-invoice"></i>
                <span>Voir factures</span>
            </a>
            <a href="{{ route('reclamations.index') }}" class="flex items-center justify-center space-x-2 bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-4 rounded-lg transition">
                <i class="fas fa-exclamation-circle"></i>
                <span>Réclamations</span>
            </a>
            <a href="{{ route('statistiques') }}" class="flex items-center justify-center space-x-2 bg-purple-600 hover:bg-purple-700 text-white px-6 py-4 rounded-lg transition">
                <i class="fas fa-chart-bar"></i>
                <span>Statistiques</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dashboardData() {
    return {
        stats: {
            total_abonnes: 0,
            factures_impayees: 0,
            reclamations: 0,
            revenus_mois: 0,
            particuliers: 0,
            professionnels: 0,
            particuliers_percent: 0,
            professionnels_percent: 0
        },
        activities: [],
        
        async loadData() {
            try {
                const token = localStorage.getItem('auth_token');
                
                // Load dashboard stats
                const response = await axios.get(`${API_URL}/statistiques/dashboard`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                this.stats = response.data;
                
                // Calculate percentages
                const total = this.stats.particuliers + this.stats.professionnels;
                if (total > 0) {
                    this.stats.particuliers_percent = (this.stats.particuliers / total * 100).toFixed(0);
                    this.stats.professionnels_percent = (this.stats.professionnels / total * 100).toFixed(0);
                }
                
                // Load recent activities (logs)
                const logsResponse = await axios.get(`${API_URL}/logs?per_page=5`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                this.activities = logsResponse.data.data.map(log => ({
                    id: log.id,
                    description: log.action,
                    date: new Date(log.created_at).toLocaleString('fr-FR')
                }));
                
            } catch (error) {
                console.error('Error loading dashboard:', error);
                if (error.response?.status === 401) {
                    window.location.href = '{{ route('login') }}';
                }
            }
        },
        
        formatMoney(amount) {
            return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
        }
    }
}
</script>
@endpush
