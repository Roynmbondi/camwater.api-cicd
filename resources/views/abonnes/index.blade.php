@extends('layouts.app')

@section('title', 'Gestion des Abonnés - CamWater')

@section('content')
<div x-data="abonnesData()" x-init="loadAbonnes()" class="fade-in">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users mr-3 text-blue-600"></i>Gestion des Abonnés
            </h1>
            <p class="text-gray-600 mt-2">Gérez tous vos abonnés en un seul endroit</p>
        </div>
        <a href="{{ route('web.abonnes.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Nouvel abonné
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Abonnés</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="pagination.total || 0"></p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card" style="border-left-color: #10b981;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Particuliers</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="stats.particuliers || 0"></p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-user text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stats-card" style="border-left-color: #f59e0b;">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Professionnels</p>
                    <p class="text-2xl font-bold text-gray-800" x-text="stats.professionnels || 0"></p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-building text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-filter mr-2"></i>Filtres de recherche
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Recherche</label>
                <input 
                    type="text" 
                    x-model="search"
                    @input.debounce="loadAbonnes()"
                    placeholder="Nom, email, téléphone..."
                    class="form-input"
                >
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Type</label>
                <select 
                    x-model="filters.type"
                    @change="loadAbonnes()"
                    class="form-input"
                >
                    <option value="">Tous les types</option>
                    <option value="particulier">Particulier</option>
                    <option value="professionnel">Professionnel</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Ville</label>
                <input 
                    type="text" 
                    x-model="filters.ville"
                    @input.debounce="loadAbonnes()"
                    placeholder="Filtrer par ville..."
                    class="form-input"
                >
            </div>
            <div class="flex items-end">
                <button 
                    @click="resetFilters()"
                    class="btn-secondary w-full"
                >
                    <i class="fas fa-redo mr-2"></i>Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-container">
        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th><i class="fas fa-user mr-2"></i>Abonné</th>
                        <th><i class="fas fa-envelope mr-2"></i>Contact</th>
                        <th><i class="fas fa-tag mr-2"></i>Type</th>
                        <th><i class="fas fa-map-marker-alt mr-2"></i>Localisation</th>
                        <th><i class="fas fa-cogs mr-2"></i>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-4"></i>
                                    <p class="text-gray-600">Chargement des abonnés...</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-if="!loading && abonnes.length === 0">
                        <tr>
                            <td colspan="5" class="text-center py-12">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-inbox text-5xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 text-lg">Aucun abonné trouvé</p>
                                    <p class="text-gray-500 text-sm">Essayez de modifier vos critères de recherche</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-for="abonne in abonnes" :key="abonne.id">
                        <tr>
                            <td>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                        <span x-text="abonne.nom ? abonne.nom.charAt(0).toUpperCase() : 'A'"></span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900" x-text="abonne.nom + ' ' + (abonne.prenom || '')"></div>
                                        <div class="text-sm text-gray-500" x-text="'#' + abonne.numero_compteur"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-gray-900" x-text="abonne.email"></div>
                                <div class="text-sm text-gray-500" x-text="abonne.telephone"></div>
                            </td>
                            <td>
                                <span 
                                    class="badge"
                                    :class="abonne.type === 'particulier' ? 'badge-success' : 'badge-info'"
                                    x-text="abonne.type === 'particulier' ? 'Particulier' : 'Professionnel'"
                                ></span>
                            </td>
                            <td>
                                <div class="text-sm text-gray-900" x-text="abonne.ville || 'Non spécifiée'"></div>
                                <div class="text-sm text-gray-500" x-text="abonne.adresse || ''"></div>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <a :href="`/abonnes/${abonne.id}`" 
                                       class="text-blue-600 hover:text-blue-800 p-2 rounded-full hover:bg-blue-100 transition"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a :href="`/abonnes/${abonne.id}/edit`" 
                                       class="text-yellow-600 hover:text-yellow-800 p-2 rounded-full hover:bg-yellow-100 transition"
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button @click="deleteAbonne(abonne.id)" 
                                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t">
            <div class="text-sm text-gray-700">
                Affichage de <span class="font-medium" x-text="pagination.from || 0"></span> à 
                <span class="font-medium" x-text="pagination.to || 0"></span> sur 
                <span class="font-medium" x-text="pagination.total || 0"></span> résultats
            </div>
            <div class="flex space-x-2">
                <button 
                    @click="changePage(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    :class="pagination.current_page === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200'"
                    class="px-4 py-2 border border-gray-300 rounded-lg bg-white transition"
                >
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="px-4 py-2 border border-gray-300 rounded-lg bg-white">
                    Page <span x-text="pagination.current_page || 1"></span> sur <span x-text="pagination.last_page || 1"></span>
                </span>
                <button 
                    @click="changePage(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    :class="pagination.current_page === pagination.last_page ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-200'"
                    class="px-4 py-2 border border-gray-300 rounded-lg bg-white transition"
                >
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function abonnesData() {
    return {
        abonnes: [],
        loading: true,
        search: '',
        filters: {
            type: '',
            ville: ''
        },
        pagination: {
            current_page: 1,
            last_page: 1,
            from: 0,
            to: 0,
            total: 0
        },
        stats: {
            particuliers: 0,
            professionnels: 0
        },
        
        async loadAbonnes(page = 1) {
            this.loading = true;
            try {
                const token = localStorage.getItem('auth_token');
                const params = new URLSearchParams({
                    page: page,
                    search: this.search,
                    type: this.filters.type,
                    ville: this.filters.ville
                });
                
                const response = await axios.get(`${API_URL}/abonnes?${params}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                this.abonnes = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    from: response.data.from,
                    to: response.data.to,
                    total: response.data.total
                };
                
                // Calculate stats
                this.stats.particuliers = this.abonnes.filter(a => a.type === 'particulier').length;
                this.stats.professionnels = this.abonnes.filter(a => a.type === 'professionnel').length;
                
            } catch (error) {
                console.error('Error loading abonnes:', error);
                if (error.response?.status === 401) {
                    window.location.href = '{{ route('login') }}';
                }
            } finally {
                this.loading = false;
            }
        },
        
        changePage(page) {
            if (page >= 1 && page <= this.pagination.last_page) {
                this.loadAbonnes(page);
            }
        },
        
        resetFilters() {
            this.search = '';
            this.filters = { type: '', ville: '' };
            this.loadAbonnes();
        },
        
        async deleteAbonne(id) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')) return;
            
            try {
                const token = localStorage.getItem('auth_token');
                await axios.delete(`${API_URL}/abonnes/${id}`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                
                alert('Abonné supprimé avec succès');
                this.loadAbonnes(this.pagination.current_page);
            } catch (error) {
                alert('Erreur lors de la suppression');
                console.error(error);
            }
        }
    }
}
</script>
@endpush
