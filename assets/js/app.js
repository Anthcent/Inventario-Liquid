
document.addEventListener('alpine:init', () => {

    // Helper for SweetAlert
    const notify = (icon, title, text = '') => {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            background: '#1e293b',
            color: '#fff'
        });
    };

    const confirmAction = async (title, text) => {
        const result = await Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            background: '#0f172a',
            color: '#fff'
        });
        return result.isConfirmed;
    };

    // Inventory Management Component
    Alpine.data('inventory', () => ({
        products: [],
        categories: [],
        cloneResults: [],
        cloneSearch: '',
        formMode: 'create', // create | edit
        margin: 0,

        // Filters
        search: '',
        filterCategory: '',

        // Searchable Category Dropdown State
        catSearch: '',
        showCatDropdown: false,
        selectedCatName: '',

        product: {
            id: null,
            sku: '',
            name: '',
            category_id: '',
            brand: '',
            is_liquid: true,
            display_unit: 'Litro',
            cost_price: '',
            price: '',
            stock: '',
            min_stock: 10,
            image: null
        },
        imagePreview: null,

        async init() {
            await this.fetchProducts();
            await this.fetchCategories();
            this.checkUrlForEdit();
        },

        checkUrlForEdit() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            if (editId) {
                // Wait for products to load, then find
                const interval = setInterval(() => {
                    if (this.products.length > 0) {
                        const item = this.products.find(p => p.id == editId);
                        if (item) {
                            this.loadForEdit(item);
                            // Clean URL
                            window.history.replaceState({}, document.title, window.location.pathname);
                        }
                        clearInterval(interval);
                    }
                }, 100);
            }
        },

        // Filter categories for the custom dropdown
        get filteredCategories() {
            if (!this.catSearch) return this.categories;
            return this.categories.filter(c => c.name.toLowerCase().includes(this.catSearch.toLowerCase()));
        },

        selectCategory(cat) {
            this.product.category_id = cat.id;
            this.selectedCatName = cat.name;
            this.showCatDropdown = false;
            this.catSearch = '';
        },

        async fetchProducts() {
            const res = await fetch('api/products.php?action=list');
            const json = await res.json();
            if (json.status === 'success') this.products = json.data;
        },

        async fetchCategories() {
            const res = await fetch('api/categories.php?action=list');
            const json = await res.json();
            if (json.status === 'success') this.categories = json.data;
        },

        get filteredProducts() {
            let result = this.products;

            // Text Search
            if (this.search) {
                const term = this.search.toLowerCase();
                result = result.filter(p =>
                    p.name.toLowerCase().includes(term) ||
                    (p.sku && p.sku.toLowerCase().includes(term))
                );
            }

            // Category Filter
            if (this.filterCategory) {
                result = result.filter(p => p.category_id == this.filterCategory);
            }

            return result;
        },

        setLiquid(val) {
            this.product.is_liquid = val;
            if (val) this.product.display_unit = 'Litro';
            else this.product.display_unit = 'Unidad';
        },

        calculateMargin() {
            const cost = parseFloat(this.product.cost_price) || 0;
            const price = parseFloat(this.product.price) || 0;
            if (price > 0 && cost > 0) {
                // Formula: ((Price - Cost) / Price) * 100
                // This gives Profit Margin %.
                // If you want Markup %, it would be ((Price - Cost) / Cost) * 100
                // Let's stick to Profit Margin as requested.
                this.margin = (((price - cost) / price) * 100).toFixed(1);
            } else {
                this.margin = 0;
            }
        },

        handleImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.product.image = file;
                this.imagePreview = URL.createObjectURL(file);
            }
        },

        // Clean Reset
        resetForm() {
            this.formMode = 'create';
            this.product = {
                id: null,
                sku: '',
                name: '',
                category_id: '',
                brand: '',
                is_liquid: true,
                display_unit: 'Litro',
                cost_price: '',
                price: '',
                stock: '',
                min_stock: 10,
                image: null
            };
            this.imagePreview = null;
            this.margin = 0;
            this.cloneSearch = '';
            this.selectedCatName = '';
            // Reset file input value
            const input = document.getElementById('fileInput');
            if (input) input.value = '';
        },

        searchForClone() {
            if (this.cloneSearch.length < 2) {
                this.cloneResults = [];
                return;
            }
            const term = this.cloneSearch.toLowerCase();
            this.cloneResults = this.products.filter(p =>
                p.name.toLowerCase().includes(term) ||
                (p.sku && p.sku.toLowerCase().includes(term))
            ).slice(0, 5);
        },

        fillForm(item) {
            // When cloning, we want to allow new image upload.
            // We set imagePreview to null so the "Click to upload" placeholder appears,
            // OR we can show the old one but allow click.
            // The user complained "no me deja agregar imagen" (won't let me add image).
            // This suggests the upload click handler might be blocked or the input not resetting.

            this.product = {
                ...item,
                id: null, // New ID
                name: item.name + ' (Copia)',
                stock: '',
                category_id: item.category_id || '',
                is_liquid: item.is_liquid == 1,
                image: null, // New file object is null
                sku: '' // Clear SKU to avoid duplicate
            };

            // Set preview to old image just for visual reference, BUT ensure file input is clear
            // Actually, to make it super clear "add new image", maybe we shouldn't show the old preview?
            // User request usually implies they want to register a SIMILAR product but likely valid to change image.
            // Let's keep the preview but ensure the DIV CLICK works.
            this.imagePreview = item.image_path ? item.image_path : null;

            // Fix Margin
            this.calculateMargin();
            this.selectedCatName = item.category_name || '';

            this.formMode = 'create';
            this.cloneResults = [];
            this.cloneSearch = '';

            // Ensure File Input is reset
            const input = document.getElementById('fileInput');
            if (input) input.value = '';

            Swal.fire({ icon: 'info', title: 'Datos Copiados', text: 'Puedes editar y agregar la nueva imagen.', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
        },

        loadForEdit(item) {
            this.product = {
                ...item,
                is_liquid: item.is_liquid == 1,
                category_id: item.category_id || '',
                image: null // Reset file input
            };
            this.selectedCatName = item.category_name || ''; // Update UI text
            this.product.stock = parseFloat(item.stock_quantity).toFixed(2);
            this.imagePreview = item.image_path ? item.image_path : null;
            this.calculateMargin();
            this.formMode = 'edit';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        async saveProduct() {
            if (!this.product.name || !this.product.price) {
                notify('error', 'Faltan datos');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'save');
            formData.append('id', this.product.id || '');
            formData.append('sku', this.product.sku);
            formData.append('name', this.product.name);
            formData.append('brand', this.product.brand);
            formData.append('category_id', this.product.category_id || '');
            formData.append('is_liquid', this.product.is_liquid ? 1 : 0);
            formData.append('display_unit', this.product.display_unit);
            formData.append('cost_price', this.product.cost_price);
            formData.append('price', this.product.price);
            formData.append('min_stock', this.product.min_stock);

            if (this.product.image) {
                formData.append('image', this.product.image);
            }

            if (this.formMode === 'create') {
                formData.append('stock', this.product.stock || 0);
            }

            const res = await fetch('api/products.php', { method: 'POST', body: formData });
            const json = await res.json();

            if (json.status === 'success') {
                notify('success', json.message);
                this.fetchProducts();
                if (this.formMode === 'create') this.resetForm();
            } else {
                notify('error', 'Error', json.message);
            }
        },

        async quickStock(product) {
            const { value: amount } = await Swal.fire({
                title: `Agregar Stock`,
                text: `Ingresa la cantidad a agregar en ${product.display_unit}`,
                input: 'number',
                inputAttributes: { step: '0.01' },
                showCancelButton: true,
                background: '#1e293b',
                color: '#fff'
            });

            if (amount) {
                const formData = new FormData();
                formData.append('action', 'update_stock');
                formData.append('id', product.id);
                formData.append('amount', amount);

                const res = await fetch('api/products.php', { method: 'POST', body: formData });
                const json = await res.json();

                if (json.status === 'success') {
                    notify('success', 'Stock actualizado');
                    this.fetchProducts();
                } else {
                    notify('error', json.message);
                }
            }
        },

        async deleteProduct(id) {
            if (await confirmAction('¿Eliminar producto?', 'Esta acción no se puede deshacer.')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                await fetch('api/products.php', { method: 'POST', body: formData });
                notify('success', 'Producto eliminado');
                this.fetchProducts();
            }
        }
    }));

    // POS Component
    Alpine.data('pos', () => ({
        products: [],
        cart: [],
        search: '',

        // Calculator State
        showCalculator: false,
        calcProduct: {},
        calcAmount: '', // Money
        calcQty: '',    // Units

        async init() {
            await this.fetchProducts();
        },

        async fetchProducts() {
            const res = await fetch('api/products.php?action=list');
            const json = await res.json();
            if (json.status === 'success') this.products = json.data;
        },

        get filteredProducts() {
            if (!this.search) return this.products;
            return this.products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
        },

        addToCart(product) {
            this.addFinalToCart(product, 1, parseFloat(product.price));
            notify('success', 'Agregado', `1 ${product.display_unit} de ${product.name}`);
        },

        addFinalToCart(product, qty, subtotal) {
            const existing = this.cart.find(i => i.id === product.id);
            if (existing) {
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
            if (price > 0 && this.calcAmount) {
                this.calcQty = (parseFloat(this.calcAmount) / price).toFixed(4);
            }
        },

        updateCalcByQty() {
            // User types Qty -> Calc Money
            let price = parseFloat(this.calcProduct.price);
            if (price > 0 && this.calcQty) {
                this.calcAmount = (parseFloat(this.calcQty) * price).toFixed(2);
            }
        },

        setFraction(fraction) {
            this.calcQty = fraction;
            this.updateCalcByQty();
        },

        addToCartFromCalc() {
            // Validate
            if (parseFloat(this.calcAmount) <= 0 || parseFloat(this.calcQty) <= 0) {
                notify('error', 'Valor Inválido');
                return;
            }

            this.addFinalToCart(this.calcProduct, this.calcQty, this.calcAmount);
            this.showCalculator = false;
            notify('success', 'Agregado', `Bs ${this.calcAmount} de ${this.calcProduct.name}`);
        },

        updateByAmount(index, amount) {
            const item = this.cart[index];
            const price = parseFloat(item.price);
            if (price > 0) {
                item.quantity_to_sell = (parseFloat(amount) / price).toFixed(4);
                item.subtotal = amount;
            }
        },

        async checkout() {
            if (this.cart.length === 0) return;

            // Final Stock Check
            for (let item of this.cart) {
                if (parseFloat(item.quantity_to_sell) > parseFloat(item.stock_quantity)) {
                    Swal.fire({ icon: 'error', title: 'Stock Insuficiente', text: `Solo tienes ${item.stock_quantity} ${item.display_unit} de ${item.name}` });
                    return;
                }
            }

            if (await confirmAction('¿Procesar Venta?', `Total: Bs ${this.cartTotal}`)) {
                const res = await fetch('api/sales.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cart: this.cart })
                });
                const json = await res.json();

                if (json.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Venta Exitosa!',
                        text: `ID Venta: #${json.sale_id}`,
                        background: '#1e293b',
                        color: '#fff'
                    });
                    this.cart = [];
                    this.fetchProducts();
                } else {
                    notify('error', 'Error', json.message);
                }
            }
        }
    }));

    // Categories Component (Separate Page logic)
    Alpine.data('categories', () => ({
        categories: [],
        showModal: false,
        current: { id: null, name: '', description: '' },

        init() { this.fetchCats(); },

        async fetchCats() {
            const res = await fetch('api/categories.php?action=list');
            const json = await res.json();
            if (json.status === 'success') this.categories = json.data;
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
            if (this.current.id) formData.append('id', this.current.id);
            formData.append('name', this.current.name);
            formData.append('description', this.current.description);

            const res = await fetch('api/categories.php', { method: 'POST', body: formData });
            const json = await res.json();

            if (json.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Guardado', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                this.fetchCats();
                this.showModal = false;
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: json.message });
            }
        },

        async remove(id) {
            if ((await Swal.fire({ title: '¿Eliminar?', icon: 'warning', showCancelButton: true })).isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);
                await fetch('api/categories.php', { method: 'POST', body: formData });
                this.fetchCats();
            }
        }
    }));
});
