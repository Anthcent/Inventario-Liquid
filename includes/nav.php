<?php
// Get current page
$currentPage = basename($_SERVER['PHP_SELF']);

// Check for low stock items for the alert badge
require_once __DIR__ . '/../config/database.php';
$lowStockCount = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM products WHERE stock_quantity <= min_stock");
    $lowStockCount = $stmt->fetchColumn();
} catch (Exception $e) { /* Ignore */ }
?>
<nav class="bg-white/90 dark:bg-black/90 backdrop-blur-md border-b border-gray-200 dark:border-white/5 fixed w-full top-0 z-40 transition-all duration-300" x-data="{ scrolled: false, mobileMenuOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Logo area -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-tr from-brand-400 to-blue-500 flex items-center justify-center shadow-lg shadow-brand-500/20">
                    <i class="fa-solid fa-bottle-droplet text-white text-sm"></i>
                </div>
                <span class="text-xl font-black bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-gray-500 dark:from-white dark:to-gray-400">
                    JabonesPOS
                </span>
            </div>
            
            <!-- Desktop Links -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-2">
                    <a href="index.php" class="<?php echo $currentPage == 'index.php' ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-cash-register"></i> Punto de Venta
                    </a>
                    
                    <a href="inventory.php" class="<?php echo $currentPage == 'inventory.php' ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-boxes-stacked"></i> Nuevo Producto
                    </a>

                    <a href="catalogue.php" class="<?php echo $currentPage == 'catalogue.php' ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2 relative">
                        <i class="fa-solid fa-list"></i> Catálogo
                        <?php if($lowStockCount > 0): ?>
                             <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[9px] text-white items-center justify-center font-bold">!</span>
                            </span>
                        <?php endif; ?>
                    </a>

                    <a href="sales_history.php" class="<?php echo $currentPage == 'sales_history.php' ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left"></i> Historial
                    </a>
                    
                    <a href="categories.php" class="<?php echo $currentPage == 'categories.php' ? 'bg-brand-600 text-white shadow-lg shadow-brand-500/20' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-white/5'; ?> px-4 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-tags"></i> Categorías
                    </a>

                    <!-- Theme Toggle -->
                    <button @click="$store.theme.toggle()" class="ml-4 w-10 h-10 rounded-full flex items-center justify-center transition-colors hover:bg-gray-100 dark:hover:bg-white/5 text-gray-500 dark:text-gray-400">
                        <i class="fa-solid text-lg" :class="$store.theme.isDark ? 'fa-sun text-yellow-500' : 'fa-moon text-brand-600'"></i>
                    </button>

                    <?php if(isset($_SESSION['admin_unlocked']) && $_SESSION['admin_unlocked']): ?>
                        <a href="lock.php" class="bg-red-500/10 hover:bg-red-500/20 text-red-500 hover:text-red-600 dark:bg-red-500/20 dark:hover:bg-red-600 dark:text-red-400 dark:hover:text-white px-3 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2 ml-2 border border-red-500/20" title="Bloquear Acceso">
                            <i class="fa-solid fa-lock"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mobile Menu Button -->
            <div class="-mr-2 flex md:hidden items-center gap-2">
                 <!-- Theme Toggle Mobile -->
                 <button @click="$store.theme.toggle()" class="w-10 h-10 rounded-full flex items-center justify-center transition-colors hover:bg-gray-100 dark:hover:bg-white/5 text-gray-500 dark:text-gray-400 mr-2">
                    <i class="fa-solid" :class="$store.theme.isDark ? 'fa-sun text-yellow-500' : 'fa-moon text-brand-600'"></i>
                </button>

                 <?php if(isset($_SESSION['admin_unlocked']) && $_SESSION['admin_unlocked']): ?>
                    <a href="lock.php" class="bg-red-500/20 text-red-400 px-3 py-2 rounded-lg text-sm">
                        <i class="fa-solid fa-lock"></i>
                    </a>
                <?php endif; ?>
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-gray-100 dark:bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white">
                    <span class="sr-only">Open main menu</span>
                    <i class="fa-solid fa-bars block h-6 w-6" :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }"></i>
                    <i class="fa-solid fa-xmark hidden h-6 w-6" :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-white/5" x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="index.php" class="text-gray-600 hover:text-brand-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium"><i class="fa-solid fa-cash-register mr-2"></i> Punto de Venta</a>
            <a href="catalogue.php" class="text-gray-600 hover:text-brand-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium"><i class="fa-solid fa-list mr-2"></i> Catálogo</a>
            <a href="inventory.php" class="text-gray-600 hover:text-brand-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium"><i class="fa-solid fa-box mr-2"></i> Nuevo Producto</a>
            <a href="sales_history.php" class="text-gray-600 hover:text-brand-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium"><i class="fa-solid fa-clock-rotate-left mr-2"></i> Historial</a>
            <a href="categories.php" class="text-gray-600 hover:text-brand-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white block px-3 py-2 rounded-md text-base font-medium"><i class="fa-solid fa-tags mr-2"></i> Categorías</a>
        </div>
    </div>
</nav>
<div class="h-16"></div> <!-- Spacer -->
