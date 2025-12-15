<?php
// JabonesPOS Installation Script
// Versión Producción - InfinityFree

// Credenciales DIRECTAS del Hosting
$host = 'sql101.infinityfree.com';
$user = 'if0_40687916';
$pass = 'wgLejdg0EC18';
$dbname = 'if0_40687916_jabon';
$port = '3306';

$message = '';
$messageType = ''; // success, error, warning

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<h3>Error Crítico de Conexión:</h3> " . $e->getMessage());
}

// Lógica de Procesamiento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ==========================================
    // ACCIÓN 1: INSTALACIÓN ESTRUCTURAL (Tablas)
    // ==========================================
    if ($action === 'install_structure') {
        try {
            $queries = [
                "CREATE TABLE IF NOT EXISTS categories (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    description TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )",
                "CREATE TABLE IF NOT EXISTS products (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    category_id INT NULL,
                    name VARCHAR(150) NOT NULL,
                    sku VARCHAR(50) NULL,
                    barcode VARCHAR(100) NULL,
                    brand VARCHAR(100) NULL,
                    is_liquid TINYINT(1) DEFAULT 1, 
                    display_unit VARCHAR(20) DEFAULT 'Litro',
                    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                    cost_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                    stock_quantity DECIMAL(10,4) NOT NULL DEFAULT 0.0000,
                    min_stock DECIMAL(10,4) DEFAULT 10.0000,
                    image_path VARCHAR(255) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
                )",
                "CREATE TABLE IF NOT EXISTS sales (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    total_amount DECIMAL(10,2) NOT NULL,
                    payment_method VARCHAR(50) DEFAULT 'cash',
                    amount_tendered DECIMAL(10,2) DEFAULT 0.00,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )",
                "CREATE TABLE IF NOT EXISTS sale_items (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    sale_id INT NOT NULL,
                    product_id INT NULL,
                    quantity DECIMAL(10,4) NOT NULL,
                    subtotal DECIMAL(10,2) NOT NULL,
                    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
                )"
            ];

            foreach ($queries as $sql) {
                $pdo->exec($sql);
            }
            
            // Categorías Base
            $pdo->exec("INSERT IGNORE INTO categories (id, name, description) VALUES 
                (1, 'Detergentes', 'Todo tipo de detergentes para ropa'),
                (2, 'Suavizantes', 'Suavizantes y aromatizantes'),
                (3, 'Limpieza Hogar', 'Desinfectantes y limpiadores generales'),
                (4, 'Automotriz', 'Productos para lavado de autos'),
                (5, 'Accesorios', 'Esponjas, paños y envases')
            ");

            $message = "¡Estructura de Base de Datos instalada correctamente!";
            $messageType = 'success';

        } catch (PDOException $e) {
            $message = "Error en Instalación: " . $e->getMessage();
            $messageType = 'error';
        }
    }

    // ==========================================
    // ACCIÓN 2: CARGAR DATOS DEMO (Seed)
    // ==========================================
    elseif ($action === 'load_demo_data') {
        try {
            // Limpiar datos anteriores (opcional, para evitar duplicados masivos)
            // $pdo->exec("TRUNCATE TABLE products"); 
            
            // Productos de Muestra Variados
            $products = [
                // Líquidos (Venta por Litro)
                [1, 'Detergente Líquido Premium Azul', 'DET-LIQ-01', 'MarcaPropia', 1, 'Litro', 15.00, 8.50, 200.00, 20],
                [1, 'Detergente Hipoalergénico Bebé', 'DET-BEB-01', 'Suave', 1, 'Litro', 18.50, 10.00, 50.00, 10],
                [2, 'Suavizante Floral Concentrado', 'SUA-FLO-01', 'AromaMax', 1, 'Litro', 12.00, 6.00, 150.00, 20],
                [3, 'Cloro Gel Tradicional', 'CLO-GEL-01', 'Limpiex', 1, 'Litro', 8.00, 4.00, 300.00, 30],
                [3, 'Desengrasante Industrial Cítrico', 'DES-IND-01', 'PowerClean', 1, 'Galón', 45.00, 25.00, 40.00, 5],
                [4, 'Shampoo con Cera para Autos', 'AUTO-SH-01', 'CarWash', 1, 'Litro', 25.00, 12.50, 80.00, 10],
                
                // Sólidos / Polvo (Venta por Kg)
                [1, 'Detergente en Polvo Bioactive', 'DET-POL-01', 'MarcaPropia', 0, 'Kg', 22.00, 11.00, 100.00, 15],
                [3, 'Bicarbonato de Sodio Limpieza', 'BIC-001', 'Natural', 0, 'Kg', 10.00, 4.00, 50.00, 5],
                
                // Unidades (Venta por Pieza)
                [5, 'Esponja Doble Cara', 'ESP-001', 'Scotch', 0, 'Unidad', 5.00, 2.00, 500.00, 50],
                [5, 'Envase Plástico 1 Litro', 'ENV-1L', 'Genérico', 0, 'Unidad', 3.50, 1.50, 200.00, 20],
                [5, 'Pack Paños Microfibra (3un)', 'MIC-PK3', 'CleanX', 0, 'Pack', 35.00, 18.00, 30.00, 5],
            ];

            $sql = "INSERT INTO products (category_id, name, sku, brand, is_liquid, display_unit, price, cost_price, stock_quantity, min_stock) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);

            foreach ($products as $p) {
                // Verificar si ya existe por SKU para no duplicar
                $check = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
                $check->execute([$p[2]]);
                if($check->rowCount() == 0) {
                    $stmt->execute($p);
                }
            }

            $message = "¡Datos de demostración cargados exitosamente! Se agregaron productos variados.";
            $messageType = 'success';

        } catch (PDOException $e) {
            $message = "Error cargando datos: " . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador & Gestor de Datos | JabonesPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-700">
        
        <!-- Header -->
        <div class="bg-gray-800 p-8 text-center border-b border-gray-700">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-server text-3xl text-white"></i>
            </div>
            <h1 class="text-2xl font-bold mb-1">Administración de Base de Datos</h1>
            <p class="text-gray-400 text-sm">configuración para <?php echo $host; ?></p>
        </div>

        <!-- Mensajes -->
        <?php if($message): ?>
            <div class="p-4 <?php echo $messageType === 'success' ? 'bg-emerald-500/10 border-l-4 border-emerald-500' : 'bg-red-500/10 border-l-4 border-red-500'; ?>">
                <div class="flex items-center gap-3">
                    <i class="fa-solid <?php echo $messageType === 'success' ? 'fa-check-circle text-emerald-400' : 'fa-exclamation-circle text-red-400'; ?> text-xl"></i>
                    <p class="<?php echo $messageType === 'success' ? 'text-emerald-400' : 'text-red-400'; ?> font-medium">
                        <?php echo $message; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Acciones -->
        <div class="p-8 space-y-6">
            
            <!-- Opción 1: Instalación Limpia -->
            <form method="POST" class="bg-gray-700/30 p-4 rounded-xl border border-gray-600 hover:border-blue-500 transition-colors">
                <input type="hidden" name="action" value="install_structure">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-500/20 rounded-lg text-blue-400">
                        <i class="fa-solid fa-layer-group text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg mb-1">Estructura Base</h3>
                        <p class="text-xs text-gray-400 mb-3">Crea las tablas necesarias (Products, Sales, Categories) si no existen.</p>
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-bold transition-colors">
                            Instalar Estructura
                        </button>
                    </div>
                </div>
            </form>

            <!-- Opción 2: Datos Demo -->
            <form method="POST" class="bg-gray-700/30 p-4 rounded-xl border border-gray-600 hover:border-purple-500 transition-colors">
                <input type="hidden" name="action" value="load_demo_data">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-purple-500/20 rounded-lg text-purple-400">
                        <i class="fa-solid fa-boxes-stacked text-xl"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg mb-1">Datos de Muestra</h3>
                        <p class="text-xs text-gray-400 mb-3">
                            Carga productos variados: líquidos, sólidos, unidades y diferentes precios para probar el sistema.
                        </p>
                        <button type="submit" class="w-full py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg text-sm font-bold transition-colors">
                            <i class="fa-solid fa-plus-circle mr-1"></i> Cargar Datos Demo
                        </button>
                    </div>
                </div>
            </form>

        </div>
        
        <div class="bg-gray-900 p-4 text-center border-t border-gray-800">
            <a href="index.php" class="text-gray-400 hover:text-white text-sm font-medium flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Volver al Sistema
            </a>
        </div>

    </div>
</body>
</html>
