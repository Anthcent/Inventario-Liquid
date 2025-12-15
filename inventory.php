<?php
require_once 'includes/auth.php';
require_pin();
require_once 'includes/header.php';
?>
<?php require_once 'includes/nav.php'; ?>

<div x-data="inventory" class="w-full px-4 md:px-8 pb-20 pt-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-6">
        <div class="text-center md:text-left">
            <h1 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400 drop-shadow-sm">
                Gestión de Productos
            </h1>
            <p class="text-gray-400 font-light mt-1" x-text="formMode === 'create' ? 'Registra un nuevo producto en el sistema.' : 'Editando producto existente.'"></p>
        </div>

        <div class="glass px-4 py-2 rounded-xl border border-gray-200 dark:border-white/10 flex items-center gap-2">
            <i class="fa-solid fa-layer-group text-brand-400"></i>
            <span class="text-gray-600 dark:text-gray-300 font-mono text-sm">Mode: <span class="font-bold text-gray-900 dark:text-white uppercase" x-text="formMode"></span></span>
            <button x-show="formMode === 'edit'" @click="resetForm()" class="ml-2 text-xs text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-white underline">Cancelar</button>
        </div>
    </div>

    <!-- MAIN EDITOR -->
    <div class="glass p-8 rounded-2xl border border-gray-200 dark:border-white/5 shadow-xl relative overflow-hidden mb-12">
        <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none">
            <i class="fa-solid fa-box text-9xl"></i>
        </div>

        <!-- Form Header -->
        <div class="flex justify-between items-center mb-6 border-b border-gray-200 dark:border-white/10 pb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <span class="bg-brand-600 w-8 h-8 rounded-lg flex items-center justify-center text-sm text-white"><i class="fa-solid" :class="formMode === 'create' ? 'fa-plus' : 'fa-pen'"></i></span>
                <span x-text="formMode === 'create' ? 'Registrar Nuevo Producto' : 'Editando: ' + product.name"></span>
            </h2>
            <button @click="resetForm()" x-show="formMode === 'edit'" class="px-4 py-2 rounded-lg text-sm bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-times"></i> Cancelar Edición
            </button>
        </div>

        <!-- Smart Clone Search (Only in Create Mode) -->
        <div x-show="formMode === 'create'" class="mb-8 relative z-20">
            <label class="text-xs font-bold text-brand-400 uppercase tracking-widest mb-2 block">Clonado Rápido (Opcional)</label>
            <div class="flex items-center gap-4">
                <div class="relative flex-grow">
                    <i class="fa-solid fa-wand-magic-sparkles absolute left-3 top-3 text-gray-500"></i>
                    <input 
                        type="text" 
                        x-model="cloneSearch"
                        @input.debounce="searchForClone()" 
                        placeholder="Buscar producto existente para copiar sus datos..." 
                        class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2.5 pl-10 pr-4 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none placeholder-gray-400 dark:placeholder-gray-600 transition-all"
                    >
                    <!-- Dropdown Results -->
                    <div x-show="cloneResults.length > 0" @click.outside="cloneResults = []" class="absolute top-12 left-0 w-full bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl overflow-hidden z-50">
                        <template x-for="item in cloneResults" :key="item.id">
                            <button @click="fillForm(item)" class="w-full text-left px-4 py-3 hover:bg-brand-600/10 dark:hover:bg-brand-600/20 flex justify-between items-center group transition-colors border-b border-gray-100 dark:border-white/5 last:border-0">
                                <div class="flex items-center gap-3">
                                    <img :src="item.image_path || 'https://via.placeholder.com/40'" class="w-8 h-8 rounded bg-black/50 object-cover">
                                    <div>
                                        <span class="font-bold text-white block text-sm" x-text="item.name"></span>
                                        <span class="text-xs text-gray-500" x-text="item.sku"></span>
                                    </div>
                                </div>
                                <span class="text-brand-400 text-xs font-bold opacity-0 group-hover:opacity-100 transistion-opacity">COPIAR</span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- THE FORM -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
            <!-- Image Section -->
            <div class="md:col-span-3 flex flex-col">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Imagen</label>
                <div class="relative w-full aspect-square bg-white dark:bg-black/40 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden group cursor-pointer hover:border-brand-500 transition-colors" @click="document.getElementById('fileInput').click()">
                    <div class="text-center" x-show="!imagePreview">
                        <i class="fa-solid fa-cloud-upload-alt text-4xl text-gray-600 group-hover:text-brand-400 transition-colors mb-2"></i>
                        <p class="text-[10px] text-gray-400 font-medium">Click para subir</p>
                    </div>
                    <img x-show="imagePreview" :src="imagePreview" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <i class="fa-solid fa-pen text-white text-2xl"></i>
                    </div>
                </div>
                <input id="fileInput" type="file" @change="handleImage" class="hidden" accept="image/*">
            </div>

            <!-- Data Section -->
            <div class="md:col-span-9 space-y-6">
                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nombre del Producto</label>
                        <input x-model="product.name" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-3 px-4 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none transition-all placeholder-gray-400 dark:placeholder-gray-600 font-bold text-lg" placeholder="Ej. Detergente Liquido">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Categoría</label>
                        
                        <!-- Custom Searchable Dropdown -->
                        <div class="relative" @click.outside="showCatDropdown = false">
                            <div @click="showCatDropdown = !showCatDropdown" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-10 pr-4 text-gray-900 dark:text-white focus-within:border-brand-500 focus-within:ring-1 focus-within:ring-brand-500 cursor-pointer flex items-center justify-between">
                                
                                <div class="flex items-center gap-2 overflow-hidden">
                                    <i class="fa-solid fa-tag text-gray-500"></i>
                                    <span x-show="selectedCatName" x-text="selectedCatName" class="font-medium"></span>
                                    <span x-show="!selectedCatName" class="text-gray-600">-- Buscar Categoría --</span>
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-500 transition-transform" :class="showCatDropdown ? 'rotate-180' : ''"></i>
                            </div>

                            <!-- Dropdown List -->
                            <div x-show="showCatDropdown" class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-dark-card border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-50 overflow-hidden max-h-60 flex flex-col animate-fade-in-up">
                                <!-- Search Input inside -->
                                <div class="p-2 border-b border-gray-200 dark:border-gray-800">
                                    <input x-model="catSearch" type="text" placeholder="Filtrar..." class="w-full bg-gray-50 dark:bg-dark-bg border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-1.5 text-sm text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none" autofocus>
                                </div>
                                
                                <div class="overflow-y-auto flex-grow custom-scrollbar">
                                    <template x-for="cat in filteredCategories" :key="cat.id">
                                        <button @click="selectCategory(cat)" class="w-full text-left px-4 py-2 hover:bg-brand-600/10 dark:hover:bg-brand-600/20 hover:text-brand-600 dark:hover:text-white text-gray-700 dark:text-gray-300 text-sm transition-colors flex justify-between items-center group">
                                            <span x-text="cat.name"></span>
                                            <i class="fa-solid fa-check text-brand-400 opacity-0 group-hover:opacity-100" x-show="product.category_id == cat.id"></i>
                                        </button>
                                    </template>
                                    <div x-show="filteredCategories.length === 0" class="p-4 text-center text-xs text-gray-500">
                                        No encontrado. <a href="categories.php" class="text-brand-400 hover:underline">Crear nueva?</a>
                                    </div>
                                </div>
                            </div>
                            <!-- Hidden Input for Form Logic if needed -->
                            <input type="hidden" :value="product.category_id">
                        </div>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">SKU</label>
                        <input x-model="product.sku" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2 px-3 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none font-mono text-sm" placeholder="CODE">
                    </div>
                    <div class="col-span-1">
                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Marca</label>
                        <input x-model="product.brand" type="text" class="w-full bg-white dark:bg-dark-bg/50 border border-gray-300 dark:border-gray-700 rounded-xl py-2 px-3 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none text-sm" placeholder="Marca">
                    </div>
                    <div class="col-span-2 bg-gray-100 dark:bg-black/20 rounded-xl p-3 border border-gray-200 dark:border-white/5 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Es Líquido?</span>
                        <div class="flex items-center gap-2">
                             <button @click="setLiquid(true)" class="px-3 py-1 rounded text-xs font-bold transition-colors" :class="product.is_liquid ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-400'">SI</button>
                             <button @click="setLiquid(false)" class="px-3 py-1 rounded text-xs font-bold transition-colors" :class="!product.is_liquid ? 'bg-emerald-600 text-white' : 'bg-gray-700 text-gray-400'">NO</button>
                        </div>
                    </div>
                </div>

                <!-- Row 3: Prices & Units -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 dark:bg-gray-800/30 p-4 rounded-xl border border-gray-200 dark:border-white/5">
                     <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Unidad</label>
                        <select x-model="product.display_unit" class="w-full bg-white dark:bg-dark-bg border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white text-sm focus:border-brand-500 focus:outline-none">
                            <template x-if="product.is_liquid">
                                <optgroup label="Volumen">
                                    <option value="Litro">Litro (L)</option>
                                    <option value="Mililitro">Mililitro (ml)</option>
                                    <option value="Galon">Galón</option>
                                    <option value="Onza">Onza</option>
                                </optgroup>
                            </template>
                            <template x-if="!product.is_liquid">
                                <optgroup label="Unidades">
                                    <option value="Unidad">Unidad (Pza)</option>
                                    <option value="Kilo">Kilo (Kg)</option>
                                    <option value="Caja">Caja</option>
                                </optgroup>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Costo ($)</label>
                        <input x-model="product.cost_price" @input="calculateMargin" type="number" step="0.01" class="w-full bg-white dark:bg-dark-bg border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-emerald-500 uppercase tracking-widest mb-2">Precio Venta ($)</label>
                        <input x-model="product.price" @input="calculateMargin" type="number" step="0.01" class="w-full bg-white dark:bg-dark-bg border border-emerald-500/50 rounded-lg px-3 py-2 text-gray-900 dark:text-white font-bold focus:border-emerald-500 focus:outline-none">
                    </div>
                </div>
                
                 <!-- Row 4: Initial Stock (Create Only) -->
                <div x-show="formMode === 'create'" class="bg-emerald-900/10 border border-emerald-500/20 p-4 rounded-xl flex items-center gap-4">
                    <i class="fa-solid fa-cubes text-emerald-500 text-xl"></i>
                    <div class="flex-grow">
                        <label class="block text-xs font-bold text-emerald-400 uppercase tracking-widest mb-1">Stock Inicial</label>
                        <input x-model="product.stock" type="number" step="0.01" class="w-full bg-transparent border-b border-emerald-500/50 text-white font-mono focus:outline-none py-1" placeholder="0.00">
                    </div>
                </div>

                <!-- Submit -->
                <button @click="saveProduct()" class="w-full bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold py-3 rounded-xl shadow-lg transition-transform active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa-solid" :class="formMode === 'create' ? 'fa-plus-circle' : 'fa-save'"></i>
                    <span x-text="formMode === 'create' ? 'Registrar Producto' : 'Guardar Cambios'"></span>
                </button>
            </div>
        </div>
    </div>

</div>

<script src="assets/js/app.js"></script>
<?php require_once 'includes/footer.php'; ?>
