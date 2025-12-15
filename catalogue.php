<?php 
require_once 'includes/auth.php';
require_pin(); 
require_once 'includes/header.php'; 
?>
<?php require_once 'includes/nav.php'; ?>

<div x-data="catalogue" class="w-full px-4 md:px-8 pb-20 pt-8">
    
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-8 gap-6">
        <div class="w-full lg:w-auto text-center lg:text-left">
            <h1 class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400 drop-shadow-sm">
                Catálogo de Productos
            </h1>
            <p class="text-gray-500 mt-2 font-light text-base md:text-lg">Visualiza, filtra y administra todo tu stock.</p>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 w-full lg:w-auto">
            <!-- Search -->
            <div class="relative w-full md:w-64">
                    <i class="fa-solid fa-search absolute left-3 top-3 text-gray-400 dark:text-gray-500"></i>
                    <input x-model="search" type="text" placeholder="Buscar producto..." class="w-full bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 rounded-xl py-2.5 pl-10 pr-4 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 transition-all shadow-lg">
            </div>
            
            <!-- Category Filter -->
            <div class="w-full md:w-48">
                <select x-model="filterCategory" class="w-full bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-2.5 text-gray-900 dark:text-white focus:outline-none focus:border-brand-500 shadow-lg appearance-none">
                    <option value="">Todas las Categorías</option>
                    <template x-for="cat in categories" :key="cat.id">
                        <option :value="cat.id" x-text="cat.name"></option>
                    </template>
                </select>
            </div>

            <!-- New Button -->
            <a href="inventory.php" class="bg-brand-600 hover:bg-brand-500 text-white px-6 py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-brand-500/20 font-bold whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> <span class="hidden md:inline">Nuevo</span>
            </a>
        </div>
    </div>

    <!-- PRODUCT GRID (CARDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <template x-for="p in filteredProducts" :key="p.id">
            <div class="glass rounded-2xl overflow-hidden border border-gray-200 dark:border-white/5 hover:border-brand-500/30 transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group relative flex flex-col h-full">
                
                <!-- Image Header -->
                <div class="relative h-48 w-full bg-gray-100 dark:bg-black/40 overflow-hidden">
                    <template x-if="p.image_path">
                        <img :src="p.image_path" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    </template>
                    <template x-if="!p.image_path">
                        <div class="w-full h-full flex items-center justify-center text-gray-700">
                             <i class="fa-solid fa-image text-4xl"></i>
                        </div>
                    </template>
                    
                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                         <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider backdrop-blur-md border border-white/10 shadow-lg" 
                            :class="p.is_liquid == 1 ? 'bg-blue-600/80 text-white' : 'bg-emerald-600/80 text-white'">
                            <i class="fa-solid" :class="p.is_liquid == 1 ? 'fa-droplet' : 'fa-cube'"></i> <span x-text="p.is_liquid == 1 ? 'LÍQ' : 'SOL'"></span>
                         </span>
                         <span class="px-2 py-1 rounded-md text-[10px] font-bold bg-gray-100 dark:bg-black/60 text-gray-700 dark:text-gray-300 backdrop-blur-md border border-gray-200 dark:border-white/10" x-text="p.category_name || 'General'"></span>
                    </div>

                    <!-- Low Stock Alert -->
                    <div x-show="parseFloat(p.stock_quantity) <= parseFloat(p.min_stock)" class="absolute bottom-0 left-0 w-full bg-red-600/90 text-white text-xs font-bold text-center py-1 animate-pulse">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> STOCK BAJO
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5 flex flex-col flex-grow">
                     <div class="flex justify-between items-start mb-2">
                        <div>
                             <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight mb-1 line-clamp-2" x-text="p.name"></h3>
                             <p class="text-xs text-gray-500 font-mono" x-text="p.sku || '#'"></p>
                        </div>
                     </div>
                     
                      <!-- Stock & Price -->
                     <div class="mt-auto pt-4 flex items-end justify-between border-t border-gray-200 dark:border-white/5">
                        <div>
                            <p class="text-[10px] uppercase text-gray-500 font-bold mb-0.5">Stock Disponible</p>
                            <div class="flex items-baseline gap-1" :class="parseFloat(p.stock_quantity) <= parseFloat(p.min_stock) ? 'text-red-500 dark:text-red-400' : 'text-emerald-500 dark:text-emerald-400'">
                                <span class="text-xl font-black" x-text="parseFloat(p.stock_quantity).toFixed(2)"></span>
                                <span class="text-xs font-bold" x-text="p.display_unit"></span>
                            </div>
                        </div>
                        <div class="text-right">
                             <p class="text-[10px] uppercase text-gray-500 font-bold mb-0.5">Precio</p>
                             <p class="text-xl font-black text-gray-900 dark:text-white">$ <span x-text="p.price"></span></p>
                        </div>
                     </div>
                </div>
                
                <!-- Actions Overlay -->
                <div class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <a :href="'inventory.php?edit=' + p.id" class="w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-500 hover:scale-110 transition-all shadow-lg shadow-blue-500/30" title="Editar">
                        <i class="fa-solid fa-pen text-lg"></i>
                    </a>
                    <button @click="quickStock(p)" class="w-12 h-12 rounded-full bg-emerald-600 text-white flex items-center justify-center hover:bg-emerald-500 hover:scale-110 transition-all shadow-lg shadow-emerald-500/30" title="Reponer Stock">
                        <i class="fa-solid fa-plus text-lg"></i>
                    </button>
                    <button @click="deleteProduct(p.id)" class="w-12 h-12 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-500 hover:scale-110 transition-all shadow-lg shadow-red-500/30" title="Eliminar">
                        <i class="fa-solid fa-trash text-lg"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>

     <!-- Empty State -->
    <div x-show="filteredProducts.length === 0" class="text-center py-20 opacity-50">
         <i class="fa-solid fa-box-open text-6xl mb-4 text-gray-600"></i>
         <p class="text-xl text-gray-400">No se encontraron productos</p>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    // Reuse similar logic but strictly for Catalogue View
    Alpine.data('catalogue', () => ({
        products: [],
        categories: [],
        search: '',
        filterCategory: '',
        
        async init() {
            await this.fetchProducts();
            await this.fetchCategories();
        },

        async fetchProducts() {
            const res = await fetch('api/products.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.products = json.data;
        },
        
        async fetchCategories() {
            const res = await fetch('api/categories.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.categories = json.data;
        },

        get filteredProducts() {
            let result = this.products;
            if(this.search) {
                const term = this.search.toLowerCase();
                result = result.filter(p => p.name.toLowerCase().includes(term) || (p.sku && p.sku.toLowerCase().includes(term)));
            }
            if(this.filterCategory) {
                result = result.filter(p => p.category_id == this.filterCategory);
            }
            return result;
        },

        async quickStock(product) {
             const { value: amount } = await Swal.fire({
                title: `Reponer Stock`,
                html: `<div class="text-left text-sm mb-2 text-gray-400">Agregando a: <strong class="text-white">${product.name}</strong></div>
                       <div class="text-left text-xs mb-4 text-gray-500">Unidad: ${product.display_unit}</div>`,
                input: 'number',
                inputPlaceholder: 'Cantidad a ingresar...',
                inputAttributes: { step: '0.01' },
                showCancelButton: true,
                confirmButtonText: 'Confirmar Ingreso',
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#334155',
                background: Alpine.store('theme').isDark ? '#1e293b' : '#fff', 
                color: Alpine.store('theme').isDark ? '#fff' : '#1e293b'
            });

            if (amount) {
                const formData = new FormData();
                formData.append('action', 'update_stock');
                formData.append('id', product.id);
                formData.append('amount', amount);
                await fetch('api/products.php', { method: 'POST', body: formData });
                Swal.fire({ icon: 'success', title: 'Stock Actualizado', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, background: '#1e293b', color: '#fff' });
                this.fetchProducts();
            }
        },
        
        async deleteProduct(id) {
            const res = await Swal.fire({
                title: '¿Eliminar producto?',
                text: "No podrás revertir esto.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Sí, eliminar',
                background: Alpine.store('theme').isDark ? '#1e293b' : '#fff',
                color: Alpine.store('theme').isDark ? '#fff' : '#1e293b'
            });

            if (res.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                await fetch('api/products.php', { method: 'POST', body: formData });
                Swal.fire({ icon: 'success', title: 'Eliminado', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false, background: '#1e293b', color: '#fff' });
                this.fetchProducts();
            }
        }
    }));
});
</script>
<?php require_once 'includes/footer.php'; ?>
