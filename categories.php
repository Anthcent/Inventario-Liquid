<?php 
require_once 'includes/auth.php';
require_pin();
require_once 'includes/header.php'; 
?>
<?php require_once 'includes/nav.php'; ?>

<div x-data="categories" class="w-full px-4 md:px-8 pt-8 pb-20">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
        <div class="text-center md:text-left">
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-cyan-400 drop-shadow-sm">
                Gestión de Categorías
            </h1>
            <p class="text-gray-500 font-light mt-1">Organiza tu inventario.</p>
        </div>
        <button @click="openModal()" class="w-full md:w-auto bg-teal-600 hover:bg-teal-500 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-teal-500/20 transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Nueva Categoría
        </button>
    </div>

    <!-- Category List -->
    <div class="glass rounded-xl overflow-hidden border border-gray-200 dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-600 dark:text-gray-400">
                <thead class="bg-gray-100 dark:bg-black/20 text-xs uppercase font-bold text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Descripción</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="cat in categories" :key="cat.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white" x-text="cat.name"></td>
                            <td class="px-6 py-4 text-sm" x-text="cat.description || '-'"></td>
                            <td class="px-6 py-4 flex justify-center gap-3">
                                <button @click="edit(cat)" class="text-blue-400 hover:text-blue-300 bg-blue-500/10 p-2 rounded-lg transition-colors"><i class="fa-solid fa-pen"></i></button>
                                <button @click="remove(cat.id)" class="text-red-400 hover:text-red-300 bg-red-500/10 p-2 rounded-lg transition-colors"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
            <div x-show="categories.length === 0" class="p-8 text-center">
                <p class="text-gray-500">No hay categorías registradas.</p>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/50 dark:bg-black/90 backdrop-blur-sm" @click="showModal = false"></div>
        <div class="glass relative w-full max-w-md bg-white dark:bg-dark-card rounded-2xl p-8 shadow-2xl animate-zoom-in">
            <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-white" x-text="current.id ? 'Editar Categoría' : 'Nueva Categoría'"></h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">Nombre</label>
                    <input x-model="current.name" type="text" class="w-full bg-gray-100 dark:bg-dark-bg border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-600 dark:text-gray-400 mb-1">Descripción</label>
                    <textarea x-model="current.description" rows="3" class="w-full bg-gray-100 dark:bg-dark-bg border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none"></textarea>
                </div>
                
                <button @click="save()" class="w-full bg-brand-600 hover:bg-brand-500 text-white font-bold py-3 rounded-lg mt-2 transition-all">
                    Guardar
                </button>
            </div>
             <button @click="showModal = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-900 dark:hover:text-white"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('categories', () => ({
        categories: [],
        showModal: false,
        current: { id: null, name: '', description: '' },

        init() { this.fetchCats(); },

        async fetchCats() {
            const res = await fetch('api/categories.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.categories = json.data;
        },

        openModal() {
            this.current = { id: null, name: '', description: '' };
            this.showModal = true;
        },

        edit(cat) {
            this.current = { ...cat };
            this.showModal = true;
        },

        async save() {
            const formData = new FormData();
            formData.append('action', 'save');
            if(this.current.id) formData.append('id', this.current.id);
            formData.append('name', this.current.name);
            formData.append('description', this.current.description);

            const res = await fetch('api/categories.php', { method: 'POST', body: formData });
            const json = await res.json();
            
            if(json.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Guardado', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                this.fetchCats();
                this.showModal = false;
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: json.message });
            }
        },

        async remove(id) {
            if((await Swal.fire({ title: '¿Eliminar?', icon: 'warning', showCancelButton: true })).isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                await fetch('api/categories.php', { method: 'POST', body: formData });
                this.fetchCats();
            }
        }
    }));
});
</script>
<?php require_once 'includes/footer.php'; ?>
