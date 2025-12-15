<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/nav.php'; ?>

<div x-data="salesHistory" class="w-full px-4 md:px-6 pb-20 pt-6">
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Historial de Ventas</h1>
        <p class="text-gray-500 text-sm">Últimas transacciones realizadas</p>
    </div>

    <!-- History Table -->
    <div class="glass rounded-xl overflow-hidden border border-gray-200 dark:border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-gray-600 dark:text-gray-400 min-w-[600px]">
                <thead class="bg-gray-100 dark:bg-black/20 text-xs uppercase font-bold text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-4">ID Venta</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Total ($)</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    <template x-for="sale in sales" :key="sale.id">
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4 text-brand-500 dark:text-brand-400 font-mono font-bold" x-text="'#' + sale.id"></td>
                            <td class="px-6 py-4 text-sm" x-text="formatDate(sale.created_at)"></td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white text-lg" x-text="'$ ' + sale.total_amount"></td>
                            <td class="px-6 py-4 text-center">
                                <button @click="viewDetails(sale)" class="bg-gray-200 dark:bg-white/10 hover:bg-gray-300 dark:hover:bg-white/20 text-gray-700 dark:text-white p-2 rounded-full transition-colors" title="Ver Detalles">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="sales.length === 0">
                        <td colspan="4" class="py-12 text-center text-gray-500">
                            <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 opacity-50"></i>
                            <p>No hay ventas registradas.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Details Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/50 dark:bg-black/90 backdrop-blur-sm" @click="showModal = false"></div>
        <div class="glass relative w-full max-w-lg bg-white dark:bg-dark-card rounded-2xl p-8 shadow-2xl animate-zoom-in">
            <div class="flex justify-between items-start mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles Venta <span x-text="'#' + currentSale.id" class="text-brand-500 dark:text-brand-400"></span></h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="formatDate(currentSale.created_at)"></p>
                </div>
                <button @click="showModal = false" class="text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <!-- Items List -->
            <div class="space-y-3 mb-6 max-h-96 overflow-y-auto custom-scrollbar pr-2">
                <template x-for="item in saleItems" :key="item.id">
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-black/20 p-3 rounded-lg border border-gray-200 dark:border-white/5">
                        <div>
                            <p class="font-bold text-gray-800 dark:text-gray-200 text-sm" x-text="item.product_name || 'Producto Eliminado'"></p>
                            <p class="text-xs text-gray-500 font-mono" x-text="item.sku"></p>
                        </div>
                        <div class="text-right">
                             <p class="font-bold text-gray-900 dark:text-white text-sm" x-text="'$ ' + item.subtotal"></p>
                             <p class="text-xs text-gray-500 dark:text-gray-400">Can: <span x-text="parseFloat(item.quantity).toFixed(2)"></span></p>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                <span class="text-gray-500 dark:text-gray-400 uppercase font-bold text-sm">Total Pagado</span>
                <span class="text-3xl font-bold text-emerald-500 dark:text-emerald-400" x-text="'$ ' + currentSale.total_amount"></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('salesHistory', () => ({
        sales: [],
        showModal: false,
        currentSale: {},
        saleItems: [],

        init() {
            this.fetchSales();
        },

        async fetchSales() {
            const res = await fetch('api/sales.php?action=list');
            const json = await res.json();
            if(json.status === 'success') this.sales = json.data;
        },

        async viewDetails(sale) {
            this.currentSale = sale;
            this.saleItems = []; // clear previous
            this.showModal = true;
            
            // Fetch Items
            const res = await fetch(`api/sales.php?action=get_details&id=${sale.id}`);
            const json = await res.json();
            if(json.status === 'success') {
                this.saleItems = json.data;
            }
        },

        formatDate(dateStr) {
            if(!dateStr) return '';
            return new Date(dateStr).toLocaleString();
        }
    }));
});
</script>
<?php require_once 'includes/footer.php'; ?>
