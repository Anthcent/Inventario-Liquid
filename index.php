<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/nav.php'; ?>

<!-- BETA WELCOME MODAL -->
<div x-data="{ showBetaModal: !localStorage.getItem('betaModalSeen') }" 
     x-show="showBetaModal" 
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4"
     style="display: none;">
    
    <!-- Animated Background Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-black via-gray-900 to-black opacity-95 backdrop-blur-xl">
        <!-- Animated Gradient Orbs -->
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-96 h-96 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-96 h-96 bg-gradient-to-r from-pink-500 to-orange-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Modal Content -->
    <div class="relative max-w-2xl w-full bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-3xl shadow-2xl border border-white/10 overflow-hidden transform transition-all animate-scale-in">
        
        <!-- Glowing Border Effect -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 opacity-20 blur-xl"></div>
        
        <!-- Content Container -->
        <div class="relative z-10 p-8 md:p-12">
            
            <!-- Animated Icon -->
            <div class="flex justify-center mb-8">
                <div class="relative">
                    <!-- Pulsing Rings -->
                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 opacity-20 animate-ping"></div>
                    <div class="absolute inset-0 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 opacity-30 animate-pulse"></div>
                    
                    <!-- Main Icon Container -->
                    <div class="relative w-24 h-24 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-purple-500/50 transform rotate-6 hover:rotate-0 transition-transform duration-500">
                        <i class="fa-solid fa-flask text-4xl text-white animate-bounce-slow"></i>
                    </div>
                    
                    <!-- Floating Sparkles -->
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full animate-ping"></div>
                    <div class="absolute -bottom-1 -left-1 w-4 h-4 bg-cyan-400 rounded-full animate-pulse"></div>
                </div>
            </div>

            <!-- Title with Gradient Text -->
            <div class="text-center mb-6">
                <h1 class="text-5xl md:text-6xl font-black mb-3 bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400 bg-clip-text text-transparent animate-gradient-x">
                    VERSIÓN DEMO
                </h1>
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="h-px w-16 bg-gradient-to-r from-transparent via-purple-500 to-transparent"></div>
                    <span class="px-4 py-1.5 bg-gradient-to-r from-orange-500 to-pink-500 text-white text-sm font-bold rounded-full shadow-lg shadow-orange-500/50 animate-pulse-slow">
                        BETA
                    </span>
                    <div class="h-px w-16 bg-gradient-to-r from-transparent via-purple-500 to-transparent"></div>
                </div>
            </div>

            <!-- Main Message -->
            <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/10 mb-6">
                <p class="text-gray-200 text-lg md:text-xl leading-relaxed text-center mb-4">
                    Bienvenido al <span class="font-bold text-transparent bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text">Sistema de Inventario Liquid</span>
                </p>
                <p class="text-gray-300 text-base md:text-lg leading-relaxed text-center">
                    Esta es una <span class="font-bold text-emerald-400">versión BETA</span> en desarrollo activo. 
                    Puedes enviar <span class="font-bold text-yellow-400">una (1) propuesta de cambios</span> 
                    para su finalización y personalización según tus necesidades.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-br from-blue-500/10 to-blue-600/10 backdrop-blur-sm rounded-xl p-4 border border-blue-500/20 text-center transform hover:scale-105 transition-transform">
                    <i class="fa-solid fa-rocket text-3xl text-blue-400 mb-2"></i>
                    <p class="text-sm text-gray-300 font-medium">En Desarrollo</p>
                </div>
                <div class="bg-gradient-to-br from-purple-500/10 to-purple-600/10 backdrop-blur-sm rounded-xl p-4 border border-purple-500/20 text-center transform hover:scale-105 transition-transform">
                    <i class="fa-solid fa-code text-3xl text-purple-400 mb-2"></i>
                    <p class="text-sm text-gray-300 font-medium">Personalizable</p>
                </div>
                <div class="bg-gradient-to-br from-pink-500/10 to-pink-600/10 backdrop-blur-sm rounded-xl p-4 border border-pink-500/20 text-center transform hover:scale-105 transition-transform">
                    <i class="fa-solid fa-star text-3xl text-pink-400 mb-2"></i>
                    <p class="text-sm text-gray-300 font-medium">1 Propuesta</p>
                </div>
            </div>

            <!-- Action Button -->
            <button @click="showBetaModal = false; localStorage.setItem('betaModalSeen', 'true')" 
                    class="w-full bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 hover:from-blue-500 hover:via-purple-500 hover:to-pink-500 text-white font-bold py-4 px-8 rounded-2xl shadow-2xl shadow-purple-500/50 transition-all transform hover:scale-105 active:scale-95 flex items-center justify-center gap-3 text-lg">
                <span>Comenzar a Explorar</span>
                <i class="fa-solid fa-arrow-right animate-bounce-horizontal"></i>
            </button>

            <!-- Footer Note -->
            <p class="text-center text-gray-500 text-xs mt-6">
                <i class="fa-solid fa-info-circle mr-1"></i>
                Este mensaje no se volverá a mostrar
            </p>
        </div>

        <!-- Decorative Corner Elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/20 to-transparent rounded-bl-full"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-purple-500/20 to-transparent rounded-tr-full"></div>
    </div>
</div>

<!-- Custom Animations Styles -->
<style>
    @keyframes gradient-x {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25% { transform: translate(20px, -50px) scale(1.1); }
        50% { transform: translate(-20px, 20px) scale(0.9); }
        75% { transform: translate(50px, 50px) scale(1.05); }
    }
    
    @keyframes scale-in {
        0% { transform: scale(0.8); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes bounce-horizontal {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(5px); }
    }
    
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .animate-gradient-x {
        background-size: 200% 200%;
        animation: gradient-x 3s ease infinite;
    }
    
    .animate-blob {
        animation: blob 7s infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    .animate-scale-in {
        animation: scale-in 0.5s ease-out;
    }
    
    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }
    
    .animate-bounce-horizontal {
        animation: bounce-horizontal 1s ease-in-out infinite;
    }
    
    .animate-pulse-slow {
        animation: pulse-slow 3s ease-in-out infinite;
    }
    
    [x-cloak] { 
        display: none !important; 
    }
</style>

<div x-data="pos" class="w-full px-2 md:px-6 h-[calc(100vh-60px)] md:h-[calc(100vh-80px)] flex flex-col lg:flex-row gap-3 md:gap-4 pt-2 md:pt-4 pb-2 md:pb-4">
    
    <!-- Left Column: Products -->
    <div class="lg:w-2/3 flex flex-col h-full bg-transparent dark:bg-dark-bg order-2 lg:order-1">
        <!-- Search Bar -->
        <div class="mb-4 relative">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-gray-400 text-lg"></i>
            </div>
            <input x-model="search" type="text" placeholder="Buscar..." 
                class="w-full bg-white dark:bg-dark-card border border-gray-200 dark:border-white/5 rounded-2xl py-3 md:py-4 pl-12 pr-4 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-brand-500 shadow-lg text-base md:text-lg">
        </div>

        <!-- Products Grid -->
        <div class="flex-grow overflow-y-auto pr-1 md:pr-2 custom-scrollbar pb-20 lg:pb-0">
             <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div @click="addToCart(product)" class="glass p-3 md:p-4 rounded-xl md:rounded-2xl border border-gray-200 dark:border-white/5 group hover:border-brand-500/30 transition-all relative overflow-hidden flex flex-col justify-between h-auto min-h-[180px] md:min-h-[200px] cursor-pointer active:scale-[0.98]">
                        
                        <!-- Header: Image or Icon -->
                        <div class="mb-2 md:mb-3 relative">
                            <!-- Image Display -->
                            <template x-if="product.image_path">
                                <div class="w-full h-24 md:h-32 rounded-lg md:rounded-xl overflow-hidden mb-2 md:mb-3 relative group-hover:scale-[1.02] transition-transform">
                                    <img :src="product.image_path" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    
                                    <!-- Stock Badge over Image -->
                                    <span class="absolute bottom-1 right-1 md:bottom-2 md:right-2 text-[10px] md:text-xs font-mono px-1.5 py-0.5 rounded bg-black/60 text-white backdrop-blur-sm border border-white/10" 
                                        :class="parseFloat(product.stock_quantity) < parseFloat(product.min_stock) ? 'text-red-300 border-red-500/50' : 'text-gray-200'">
                                        Stock: <span x-text="parseFloat(product.stock_quantity).toFixed(2)"></span>
                                    </span>
                                </div>
                            </template>
                            
                            <!-- Fallback Icon Display -->
                            <template x-if="!product.image_path">
                                <div class="flex justify-between items-start mb-2 md:mb-3">
                                    <div class="w-8 h-8 md:w-12 md:h-12 rounded-lg md:rounded-xl flex items-center justify-center shadow-lg shadow-brand-500/10"
                                        :class="product.is_liquid == 1 ? 'bg-gradient-to-br from-blue-500/20 to-blue-600/10 text-blue-400' : 'bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 text-emerald-400'">
                                        <i class="fa-solid text-sm md:text-xl" :class="product.is_liquid == 1 ? 'fa-faucet-drip' : 'fa-box'"></i>
                                    </div>
                                    <span class="text-[10px] md:text-xs font-mono px-1.5 py-0.5 rounded bg-gray-200 dark:bg-black/40 border border-transparent" 
                                        :class="parseFloat(product.stock_quantity) < parseFloat(product.min_stock) ? 'text-red-400 border-red-500/30 bg-red-500/10' : 'text-gray-400'">
                                        Stock: <span x-text="parseFloat(product.stock_quantity).toFixed(2)"></span>
                                    </span>
                                </div>
                            </template>
                        </div>

                        <!-- Product Info -->
                        <div class="mb-2 md:mb-4 flex-grow">
                            <h3 class="font-bold text-gray-800 dark:text-gray-100 leading-tight mb-0.5 text-xs md:text-sm line-clamp-2" x-text="product.name"></h3>
                            <p class="text-[10px] text-gray-500 truncate" x-text="product.category || 'General'"></p>
                        </div>

                        <!-- Price & Actions -->
                        <div>
                            <div class="text-sm md:text-xl font-bold text-gray-900 dark:text-white mb-2 md:mb-3">
                                $ <span x-text="product.price"></span>
                                <span class="text-[10px] md:text-xs font-normal text-gray-500">/ <span x-text="product.display_unit"></span></span>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="grid grid-cols-2 gap-1 md:gap-2">
                                <!-- Fast Add (1 Unit) -->
                                <button @click.stop="addToCart(product)" class="bg-gray-700 hover:bg-gray-600 text-white py-1.5 md:py-2 rounded-lg text-xs md:text-sm font-medium transition-colors flex items-center justify-center gap-1 active:scale-95">
                                    <i class="fa-solid fa-plus"></i> 1
                                </button>
                                
                                <!-- Custom Add (Calculator) -->
                                <button @click.stop="openCalculator(product)" class="bg-brand-600 hover:bg-brand-500 text-white py-1.5 md:py-2 rounded-lg text-xs md:text-sm font-medium transition-colors flex items-center justify-center gap-1 shadow-lg shadow-brand-500/20 active:scale-95">
                                    <i class="fa-solid fa-calculator"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
            
            <div x-show="products.length === 0" class="text-center py-20 opacity-50 animate-pulse">
                <i class="fa-solid fa-spinner fa-spin text-4xl mb-4"></i>
                <p>Cargando productos...</p>
            </div>
        </div>
    </div>

    <!-- Right Column: Cart (Mobile Optimized) -->
    <div class="lg:w-1/3 flex flex-col lg:h-full bg-white dark:bg-dark-card rounded-t-3xl lg:rounded-3xl border-t lg:border border-gray-200 dark:border-white/5 shadow-[0_-10px_40px_rgba(0,0,0,0.2)] lg:shadow-2xl overflow-hidden z-30 fixed lg:static bottom-0 left-0 right-0 h-[80px] lg:h-auto transition-all duration-300"
         :class="{'h-[80vh]': showMobileCart}"
         order="1">
        
        <!-- Cart Toggle (Mobile Only) -->
        <div @click="showMobileCart = !showMobileCart" class="lg:hidden w-full flex justify-center pt-2 pb-1 cursor-pointer">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <!-- Cart Header -->
        <div class="px-6 py-3 lg:p-6 border-b border-gray-200 dark:border-white/5 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 flex justify-between items-center cursor-pointer lg:cursor-default"
             @click="showMobileCart = !showMobileCart">
            <h2 class="text-lg lg:text-xl font-bold flex items-center gap-3 text-gray-900 dark:text-white">
                <div class="bg-brand-500 p-2 rounded-lg text-white">
                    <i class="fa-solid fa-cart-shopping text-sm lg:text-base"></i>
                </div>
                <span>Ticket</span>
                <span class="lg:hidden text-sm bg-brand-100 text-brand-600 px-2 py-0.5 rounded-full" x-show="cart.length > 0">
                    <span x-text="cart.length"></span> items
                </span>
            </h2>
            <div class="lg:hidden font-bold text-brand-500" x-text="'$' + parseFloat(cartTotal).toFixed(2)"></div>
        </div>

        <!-- Cart Items List -->
        <div class="flex-grow overflow-y-auto p-4 space-y-4 custom-scrollbar">
             <template x-for="(item, index) in cart" :key="index">
                <div class="bg-gray-50 dark:bg-dark-bg rounded-xl p-4 border border-gray-200 dark:border-white/5 relative group animate-fade-in-right">
                    
                    <!-- Remove Button -->
                    <button @click="removeFromCart(index)" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-400 text-white rounded-full w-7 h-7 flex items-center justify-center shadow-lg transition-transform hover:scale-110 z-10">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-2/3">
                            <h4 class="font-bold text-gray-800 dark:text-white text-sm" x-text="item.name"></h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <button @click.stop="decrementItem(index)" class="w-6 h-6 rounded bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-white transition-colors">
                                        <i class="fa-solid fa-minus text-[10px]"></i>
                                    </button>
                                    <span class="font-mono text-gray-800 dark:text-white text-md">
                                        <span x-text="parseFloat(item.quantity_to_sell).toFixed(2)"></span> <span x-text="item.display_unit" class="text-xs text-gray-500 dark:text-gray-400"></span>
                                    </span>
                                </div>
                                <div class="text-xs text-brand-400 mt-1">
                                    x $ <span x-text="item.price"></span>
                                </div>
                        </div>
                        <div class="text-right">
                             <div class="relative">
                                <span class="absolute left-1.5 top-1 text-gray-500 text-[10px] z-10">$</span>
                                <input type="number" step="0.5" 
                                    :value="item.subtotal" 
                                    @input="updateByAmount(index, $el.value)"
                                    class="w-20 bg-gray-200 dark:bg-black/40 border border-gray-300 dark:border-gray-700 rounded text-right pr-1 py-0.5 text-sm text-gray-900 dark:text-white focus:border-brand-500 focus:outline-none font-bold">
                            </div>
                        </div>
                    </div>
                </div>
             </template>

             <div x-show="cart.length === 0" class="h-full flex flex-col items-center justify-center text-gray-600 opacity-60">
                 <i class="fa-solid fa-cash-register text-5xl mb-4"></i>
                 <p class="text-sm">Agrega productos para comenzar</p>
             </div>
        </div>

        <!-- Pay Section -->
        <div class="p-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-white/5">
            <div class="flex justify-between items-end mb-6">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Total a Pagar</span>
                <span class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter">$ <span x-text="cartTotal"></span></span>
            </div>
            
            <button @click="openCheckout()" 
                :disabled="cart.length === 0"
                class="w-full bg-gradient-to-r from-brand-600 to-indigo-600 hover:from-brand-500 hover:to-indigo-500 text-white font-bold py-4 rounded-2xl transition-all shadow-[0_0_20px_rgba(14,165,233,0.3)] disabled:opacity-50 disabled:cursor-not-allowed text-lg flex justify-center items-center gap-3 transform active:scale-95">
                <span>Cobrar</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <!-- CALCULATOR MODAL -->
    <div x-show="showCalculator" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md" @click="showCalculator = false"></div>
        <div class="glass relative w-full max-w-sm bg-white dark:bg-dark-card rounded-3xl p-6 shadow-2xl transform transition-all animate-zoom-in">
            
            <div class="text-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1" x-text="calcProduct.name"></h3>
                <p class="text-sm text-brand-400">Precio: $ <span x-text="calcProduct.price"></span> / <span x-text="calcProduct.display_unit"></span></p>
            </div>

            <!-- The Big Inputs -->
            <div class="space-y-6">
                <!-- Group 1: Money -->
                <div class="relative group">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Monto a Vender ($)</label>
                    <div class="flex items-center bg-gray-50 dark:bg-dark-bg border-2 border-brand-500/50 rounded-xl overflow-hidden focus-within:border-brand-500 transition-colors">
                        <div class="px-4 text-brand-500 font-bold text-xl">$</div>
                        <input x-model="calcAmount" @input="updateCalcByAmount()" type="number" step="0.1" class="w-full bg-transparent border-none text-gray-900 dark:text-white text-3xl font-bold p-3 focus:ring-0 text-right placeholder-gray-400 dark:placeholder-gray-700" placeholder="0.00">
                    </div>
                </div>

                <!-- Group 2: Quantity -->
                <div class="relative group">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1 ml-1">Cantidad (<span x-text="calcProduct.display_unit"></span>)</label>
                    <div class="flex items-center bg-gray-50 dark:bg-dark-bg border-2 border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden focus-within:border-gray-400 dark:focus-within:border-white/50 transition-colors">
                        <div class="px-4 text-gray-500 font-bold text-xl"><i class="fa-solid fa-scale-balanced"></i></div>
                        <input x-model="calcQty" @input="updateCalcByQty()" type="number" step="0.001" class="w-full bg-transparent border-none text-gray-900 dark:text-white text-3xl font-bold p-3 focus:ring-0 text-right placeholder-gray-400 dark:placeholder-gray-700" placeholder="0.000">
                    </div>
                </div>
            </div>

            <!-- Quick Fractions (Only for Liquids) -->
            <div class="grid grid-cols-4 gap-2 mt-6" x-show="calcProduct.is_liquid == 1">
                <button @click="setFraction(0.25)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">1/4 L</button>
                <button @click="setFraction(0.5)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">1/2 L</button>
                <button @click="setFraction(1.0)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">1 L</button>
                 <button @click="setFraction(2.0)" class="py-2 rounded-lg bg-gray-800 hover:bg-gray-700 text-xs text-gray-300 font-mono transition-colors">2 L</button>
            </div>

            <!-- Action -->
            <button @click="addToCartFromCalc()" class="w-full mt-8 bg-brand-600 hover:bg-brand-500 text-white font-bold py-4 rounded-xl shadow-lg transition-all text-lg flex justify-center items-center gap-2">
                <i class="fa-solid fa-plus-circle"></i> Agregar a la Venta
            </button>
            
            <button @click="showCalculator = false" class="absolute top-4 right-4 text-gray-500 hover:text-white">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
    </div>



    <!-- CHECKOUT MODAL -->
    <div x-show="showCheckoutModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-md" @click="showCheckoutModal = false"></div>
        <div class="glass relative w-full max-w-2xl bg-white dark:bg-dark-card rounded-3xl p-6 shadow-2xl transform transition-all animate-zoom-in flex flex-col md:flex-row gap-6">
            
            <!-- Left: Order Summary -->
            <div class="w-full md:w-1/2 border-b md:border-b-0 md:border-r border-gray-200 dark:border-white/10 pr-0 md:pr-6 pb-6 md:pb-0">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-brand-500"></i> Resumen del Pedido
                </h3>
                <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                    <template x-for="item in cart" :key="item.id">
                        <div class="flex justify-between items-center text-sm">
                            <div>
                                <p class="text-gray-700 dark:text-gray-300 font-medium" x-text="item.name"></p>
                                <p class="text-xs text-gray-500">
                                    <span x-text="parseFloat(item.quantity_to_sell).toFixed(2)"></span> x $ <span x-text="item.price"></span>
                                </p>
                            </div>
                            <span class="text-brand-400 font-bold">$ <span x-text="item.subtotal"></span></span>
                        </div>
                    </template>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/10 flex justify-between items-end">
                    <span class="text-gray-500 dark:text-gray-400">Total a Pagar</span>
                    <span class="text-2xl font-black text-gray-900 dark:text-white">$ <span x-text="cartTotal"></span></span>
                </div>
            </div>

            <!-- Right: Payment -->
            <div class="w-full md:w-1/2 flex flex-col">
                 <h3 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-wallet text-emerald-500"></i> Método de Pago
                </h3>
                
                <!-- Payment Method Toggle -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <button @click="paymentMethod = 'cash'; updateChange()" 
                        class="p-3 rounded-xl border transition-all flex flex-col items-center gap-2"
                        :class="paymentMethod === 'cash' ? 'bg-emerald-500/20 border-emerald-500 text-emerald-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                        <i class="fa-solid fa-money-bill-wave text-xl"></i>
                        <span class="text-xs font-bold">Efectivo</span>
                    </button>
                    <button @click="paymentMethod = 'card'"
                        class="p-3 rounded-xl border transition-all flex flex-col items-center gap-2"
                        :class="paymentMethod === 'card' ? 'bg-blue-500/20 border-blue-500 text-blue-700 dark:text-white' : 'bg-gray-100 dark:bg-gray-800 border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700'">
                        <i class="fa-solid fa-credit-card text-xl"></i>
                        <span class="text-xs font-bold">Tarjeta / QR</span>
                    </button>
                </div>

                <!-- Cash Payment Inputs -->
                <div x-show="paymentMethod === 'cash'" class="space-y-4 animate-fade-in">
                     <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Monto Recibido</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-emerald-500 font-bold">$</span>
                            <input x-ref="payInput" x-model="paymentAmount" @input="updateChange()" type="number" step="0.1" 
                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl py-3 pl-10 pr-4 text-gray-900 dark:text-white font-bold text-lg focus:border-emerald-500 focus:outline-none placeholder-gray-400 dark:placeholder-gray-600"
                                placeholder="0.00">
                        </div>
                     </div>
                     
                     <div class="bg-gray-100 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200 dark:border-white/5">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400 text-sm">Cambio / Vuelto</span>
                            <span class="text-xl font-bold" :class="change < 0 ? 'text-red-400' : 'text-emerald-400'">
                                $ <span x-text="change"></span>
                            </span>
                        </div>
                     </div>
                </div>
                
                <!-- Card Payment Info -->
                <div x-show="paymentMethod === 'card'" class="flex-grow flex flex-col items-center justify-center text-center p-4 bg-blue-500/5 rounded-xl border border-blue-500/20 animate-fade-in">
                    <i class="fa-solid fa-qrcode text-4xl text-blue-400 mb-3"></i>
                    <p class="text-sm text-blue-200">Confirma la transferencia o pago con tarjeta por el monto exacto.</p>
                </div>

                <div class="mt-auto pt-6">
                    <button @click="processSale()" 
                        :disabled="paymentMethod === 'cash' && (parseFloat(change) < 0 || !paymentAmount)"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold py-4 rounded-xl shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2">
                        <span>Confirmar Pago</span>
                        <i class="fa-solid fa-check-circle"></i>
                    </button>
                    <button @click="showCheckoutModal = false" class="w-full mt-3 py-2 text-gray-500 hover:text-white text-sm transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>

            <button @click="showCheckoutModal = false" class="absolute top-4 right-4 text-gray-500 hover:text-white md:hidden">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
    </div>

</div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('pos', () => ({
        products: [],
        cart: [],
        search: '',
        
        // Calculator State
        showCalculator: false,
        calcProduct: {},
        calcAmount: '', // Money
        calcQty: '',
        
        // Checkout State
        showCheckoutModal: false,
        paymentMethod: 'cash', // cash, card, qr
        paymentAmount: '',
        change: 0,
        tempSaleId: null, // For printing after success
        
        async init() {
            await this.fetchProducts();
        },

        async fetchProducts() {
            const res = await fetch('api/products.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.products = json.data;
        },

        get filteredProducts() {
            if(!this.search) return this.products;
            return this.products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
        },

        // --- Core POS Logic ---
        
        addToCart(product) {
            this.addFinalToCart(product, 1, parseFloat(product.price));
            notify('success', 'Agregado', `1 ${product.display_unit} de ${product.name}`);
        },

        addFinalToCart(product, qty, subtotal) {
             const existing = this.cart.find(i => i.id === product.id);
             if(existing) {
                 // Update existing
                 let newQty = parseFloat(existing.quantity_to_sell) + parseFloat(qty);
                 existing.quantity_to_sell = newQty.toFixed(4);
                 existing.subtotal = (parseFloat(existing.subtotal) + parseFloat(subtotal)).toFixed(2);
             } else {
                 // Add new
                 this.cart.push({
                     ...product,
                     quantity_to_sell: parseFloat(qty).toFixed(4),
                     subtotal: parseFloat(subtotal).toFixed(2)
                 });
             }
        },

        removeFromCart(index) {
            this.cart.splice(index, 1);
        },

        decrementItem(index) {
            const item = this.cart[index];
            let currentQty = parseFloat(item.quantity_to_sell);
            let unitPrice = parseFloat(item.price);

            if(currentQty > 1) {
                // If more than 1, decrement by 1
                let newQty = currentQty - 1;
                item.quantity_to_sell = newQty.toFixed(4);
                item.subtotal = (newQty * unitPrice).toFixed(2);
            } else {
                this.removeFromCart(index);
            }
        },

        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + parseFloat(item.subtotal || 0), 0).toFixed(2);
        },
        
        // --- Calculator Logic ---

        openCalculator(product) {
            this.calcProduct = product;
            this.calcQty = 1;
            this.calcAmount = parseFloat(product.price).toFixed(2);
            this.showCalculator = true;
        },

        updateCalcByAmount() {
            // User types Money -> Calc Qty
            let price = parseFloat(this.calcProduct.price);
            if(price > 0 && this.calcAmount) {
                this.calcQty = (parseFloat(this.calcAmount) / price).toFixed(4);
            }
        },

        updateCalcByQty() {
            // User types Qty -> Calc Money
            let price = parseFloat(this.calcProduct.price);
            if(price > 0 && this.calcQty) {
                this.calcAmount = (parseFloat(this.calcQty) * price).toFixed(2);
            }
        },
        
        setFraction(fraction) {
            this.calcQty = fraction;
            this.updateCalcByQty();
        },

        addToCartFromCalc() {
            // Validate
            if(parseFloat(this.calcAmount) <= 0 || parseFloat(this.calcQty) <= 0) {
                 notify('error', 'Valor Inválido');
                 return;
            }
            
            this.addFinalToCart(this.calcProduct, this.calcQty, this.calcAmount);
            this.showCalculator = false;
            notify('success', 'Agregado', `$ ${this.calcAmount} de ${this.calcProduct.name}`);
        },

        updateByAmount(index, amount) {
             const item = this.cart[index];
             const price = parseFloat(item.price);
             if(price > 0) {
                 item.quantity_to_sell = (parseFloat(amount) / price).toFixed(4);
                 item.subtotal = amount;
             }
         },

        // --- Checkout Logic ---

        openCheckout() {
            if(this.cart.length === 0) return;
            
            // Stock Check
            for(let item of this.cart) {
                if(parseFloat(item.quantity_to_sell) > parseFloat(item.stock_quantity)) {
                    Swal.fire({ icon: 'error', title: 'Stock Insuficiente', text: `Solo tienes ${item.stock_quantity} ${item.display_unit} de ${item.name}` });
                    return;
                }
            }

            this.paymentMethod = 'cash';
            this.paymentAmount = '';
            this.change = 0;
            this.showCheckoutModal = true;
            
            // Focus input after modal opens
            // Focus input after modal opens
            setTimeout(() => {
                if(this.$refs.payInput) this.$refs.payInput.focus();
            }, 100);
        },

        updateChange() {
            if (this.paymentAmount) {
                this.change = (parseFloat(this.paymentAmount) - parseFloat(this.cartTotal)).toFixed(2);
            } else {
                this.change = 0;
            }
        },

        async processSale() {
            // Validate Payment
            if (this.paymentMethod === 'cash') {
                if (parseFloat(this.paymentAmount) < parseFloat(this.cartTotal)) {
                     Swal.fire({ icon: 'error', title: 'Monto Insuficiente', text: 'El monto recibido es menor al total.' });
                     return;
                }
            } else {
                // Card/QR: Assume exact amount or just proceed
                this.paymentAmount = this.cartTotal;
            }

            const payload = {
                cart: this.cart,
                payment_method: this.paymentMethod,
                amount_tendered: this.paymentAmount || this.cartTotal
            };

            const res = await fetch('api/sales.php?action=save', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json();
            
            if(json.status === 'success') {
                this.showCheckoutModal = false;
                this.tempSaleId = json.sale_id;
                
                // Success Action
                Swal.fire({
                    icon: 'success',
                    title: '¡Venta Exitosa!',
                    html: `
                        <p class="mb-4">Total Cobrado: <b>$ ${this.cartTotal}</b></p>
                        ${this.paymentMethod === 'cash' ? `<p class="text-lg">Cambio: <b class="text-emerald-400">$ ${this.change}</b></p>` : ''}
                    `,
                    showCancelButton: true,
                    confirmButtonText: '<i class="fa-solid fa-print"></i> Imprimir Ticket',
                    cancelButtonText: 'Cerrar',
                    background: '#0f172a',
                    color: '#fff',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.printTicket(this.tempSaleId);
                    }
                    this.cart = [];
                    this.fetchProducts();
                });

            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: json.message });
            }
        },
        
        printTicket(id) {
            const width = 350;
            const height = 600;
            const left = (screen.width - width) / 2;
            const top = (screen.height - height) / 2;
            window.open(`ticket.php?id=${id}`, '_blank', `width=${width},height=${height},top=${top},left=${left}`);
        }

    }));
});
</script>

<?php require_once 'includes/footer.php'; ?>
