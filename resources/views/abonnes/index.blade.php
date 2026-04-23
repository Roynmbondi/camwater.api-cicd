@extends('layouts.app')

@section('title', 'Gestion des Abonnés - CamWater')

@section('content')
<div x-data="abonnesData()" x-init="loadAbonnes()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                <i class="fas fa-users mr-3"></i>Gestion des Abonnés
            </h1>
            <p class="text-gray-600 mt-2">Liste de tous les abonnés</p>
        </div>
        <a href="{{ route('abonnes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i>Nouvel abonné
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Recherche</label>
                <input 
                    type="text" 
                    x-model="search"
                    @input.debounce="loadAbonnes()"
                    placeholder="Nom, email, téléphone..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Type</label>
                <select 
                    x-model="filters.type"
                    @change="loadAbonnes()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Tous</option>
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
                    placeholder="Ville..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <div class="flex items-end">
                <button 
                    @click="resetFilters()"
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
                >
                    <i class="fas fa-redo mr-2"></i>Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-if="loading">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center">
                                <i class="fas fa-spinner fa-spin text-2xl text-blue-600"></i>
                                <p class="text-gray-600 mt-2">Chargement...</p>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-if="!loading && abonnes.length === 0">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Aucun abonné trouvé</p>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-for="abonne in abonnes" :key="abonne.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="abonne.nom"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="abonne.email"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="abonne.telephone"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span 
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="abonne.type === 'particulier' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
                                    x-text="abonne.type"
                                ></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="abonne.ville || '-'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a :href="`/abonnes/${abonne.id}`" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a :href="`/abonnes/${abonne.id}/edit`" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button @click="deleteAbonne(abonne.id)" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t">
            <div class="text-sm text-gray-700">
                Affichage de <span class="font-medium" x-text="pagination.from"></span> à 
                <span class="font-medium" x-text="pagination.to"></span> sur 
                <span class="font-medium" x-text="pagination.total"></span> résultats
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
                    Page <span x-text="pagination.current_page"></span> sur <span x-text="pagination.last_page"></span>
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
