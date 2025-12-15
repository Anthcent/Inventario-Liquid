<?php
// JabonesPOS Smart Installer
// Versión: Auto-Detect Password + Updater

// Configuración Base
$host = 'sql101.infinityfree.com';
$user = 'if0_40687916';
$dbname = 'if0_40687916_jabon';
$port = '3306';

// LISTA DE INTELIGENCIA DE CONTRASEÑAS
$possible_passwords = [
    'wgLejdg0EC18', // Con número 1 (Correcta)
    'wgLejdg0ECl8', // Con letra l
    'wgLejdg0ECI8', // Con letra I
];

$pdo = null;
$connected_pass = null;
$message = '';
$messageType = '';

// 1. INTENTO DE CONEXIÓN INTELIGENTE
foreach ($possible_passwords as $try_pass) {
    try {
        $test_pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $try_pass);
        $test_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo = $test_pdo;
        $connected_pass = $try_pass;
        break; 
    } catch (PDOException $e) { continue; }
}

if (!$pdo) {
    die("<h1 style='color:red'>Error Fatal: No se pudo conectar con ninguna contraseña.</h1>");
}

// 2. AUTO-CORREGIR CONFIG.PHP
$configFile = 'config/database.php';
$configContent = "<?php
/**
 * Configuración de Base de Datos - Auto-Generada
 */
\$host = '$host';
\$dbname = '$dbname';
\$username = '$user';
\$password = '$connected_pass';
\$port = '$port';

try {
    \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException \$e) {
    die(\"Error DB: \" . \$e->getMessage());
}
?>";
@file_put_contents($configFile, $configContent);


// ==========================================
// PROCESAMIENTO DE ACCIONES
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ACCIÓN: INSTALAR / REPARAR TABLAS
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
            
            // Seed Categories
            $pdo->exec("INSERT IGNORE INTO categories (id, name, description) VALUES 
                (1, 'Detergentes', 'Todo tipo de detergentes'),
                (2, 'Suavizantes', 'Suavizantes y aromas'),
                (3, 'Limpieza Hogar', 'Desinfectantes y limpieza'),
                (4, 'Automotriz', 'Productos autos'),
                (5, 'Accesorios', 'Esponjas y envases')
            ");

            $message = "¡Estructura instalada correctamente!";
            $messageType = 'success';

        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $messageType = 'error';
        }
    }
    // ACCIÓN: ACTUALIZAR ESTRUCTURA (ALTER TABLE)
    elseif ($action === 'update_structure') {
        try {
            // Intentar agregar la columna image_path
            // Usamos un bloque try silencioso específico para esto por si ya existe
            try {
                $pdo->exec("ALTER TABLE products ADD COLUMN image_path VARCHAR(255) NULL DEFAULT NULL AFTER min_stock");
                $msg_col = "Columna 'image_path' agregada.";
            } catch (Exception $ex) {
                $msg_col = "La columna 'image_path' ya existía.";
            }

            $message = "¡Actualización completada! $msg_col";
            $messageType = 'success';
        } catch (Exception $e) {
            $message = "Error Update: " . $e->getMessage();
            $messageType = 'error';
        }
    }
    // ACCIÓN: CARGAR DATOS DEMO
    elseif ($action === 'load_demo_data') {
        try {
            // Demo Data con Imágenes
            $products = [
                // Líquidos
                [1, 'Detergente Líquido Premium', 'DET-LIQ-01', 'MarcaPropia', 1, 'Litro', 15.00, 8.50, 200.00, 20, 'https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400&q=80'],
                [1, 'Detergente Bebé Hipoalergénico', 'DET-BEB-01', 'Suave', 1, 'Litro', 18.50, 10.00, 50.00, 10, 'https://images.unsplash.com/photo-1528740561666-dc24705f08a7?w=400&q=80'],
                [2, 'Suavizante Floral Intense', 'SUA-FLO-01', 'AromaMax', 1, 'Litro', 12.00, 6.00, 150.00, 20, 'https://images.unsplash.com/photo-1626379953822-baec19c3accd?w=400&q=80'],
                [3, 'Cloro Gel Tradicional', 'CLO-GEL-01', 'Limpiex', 1, 'Litro', 8.00, 4.00, 300.00, 30, 'https://images.unsplash.com/photo-1585832770485-e68a5dbfad52?w=400&q=80'],
                [3, 'Desengrasante Cítrico', 'DES-IND-01', 'PowerClean', 1, 'Galón', 45.00, 25.00, 40.00, 5, 'https://images.unsplash.com/photo-1628191139360-4083564d03fd?w=400&q=80'],
                [4, 'Shampoo Autos Cera', 'AUTO-SH-01', 'CarWash', 1, 'Litro', 25.00, 12.50, 80.00, 10, 'https://images.unsplash.com/photo-1582268611958-ebfd161ef9cf?w=400&q=80'],
                // Sólidos
                [1, 'Detergente Polvo Bio', 'DET-POL-01', 'Bio', 0, 'Kg', 22.00, 11.00, 100.00, 15, 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?w=400&q=80'],
                [5, 'Esponja Doble Fibra', 'ESP-001', 'Scotch', 0, 'Unidad', 5.00, 2.00, 500.00, 50, 'https://plus.unsplash.com/premium_photo-1678248747443-4672e8739665?w=400&q=80'],
            ];

            // Insertar o Actualizar
            $sql = "INSERT INTO products (category_id, name, sku, brand, is_liquid, display_unit, price, cost_price, stock_quantity, min_stock, image_path) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            
            // Query para actualizar imágenes si ya existe el SKU
            $updateImg = $pdo->prepare("UPDATE products SET image_path = ? WHERE sku = ?");

            $countNew = 0;
            $countUpd = 0;

            foreach ($products as $p) {
                // Verificar existencia
                $check = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
                $check->execute([$p[2]]);
                
                if($check->rowCount() == 0) {
                    // Si no existe, insertar
                    $stmt->execute($p);
                    $countNew++;
                } else {
                    // Si existe, actualizar imagen
                    $updateImg->execute([$p[10], $p[2]]);
                    $countUpd++;
                }
            }

            $message = "¡Listo! $countNew productos creados, $countUpd imágenes actualizadas.";
            $messageType = 'success';
        } catch (Exception $e) {
            $message = "Error Carga: " . $e->getMessage();
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Soporte JabonesPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-700">
        
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 text-center">
            <h1 class="text-2xl font-bold mb-1">🔧 Panel de Mantenimiento</h1>
            <p class="text-emerald-100 text-sm">Conectado a: <?php echo $host; ?></p>
        </div>

        <div class="p-8 space-y-4">
            
            <?php if($message): ?>
                <div class="p-4 rounded-lg text-center font-bold mb-4 <?php echo $messageType === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-4">
                <!-- Opción 1: Instalar desde Cero -->
                <form method="POST">
                    <input type="hidden" name="action" value="install_structure">
                    <button class="w-full bg-blue-600 hover:bg-blue-500 p-4 rounded-xl flex items-center justify-center gap-3 font-bold transition-all border border-blue-400/20">
                        <i class="fa-solid fa-server"></i> 1. Instalar Tablas (Si está vacío)
                    </button>
                </form>

                <!-- Opción 2: Actualizar Estructura (NUEVA) -->
                <form method="POST">
                    <input type="hidden" name="action" value="update_structure">
                    <button class="w-full bg-orange-600 hover:bg-orange-500 p-4 rounded-xl flex items-center justify-center gap-3 font-bold transition-all border border-orange-400/20">
                        <i class="fa-solid fa-wrench"></i> 2. Actualizar DB (Agregar Imágenes)
                    </button>
                </form>

                <!-- Opción 3: Cargar Datos -->
                <form method="POST">
                     <input type="hidden" name="action" value="load_demo_data">
                    <button class="w-full bg-purple-600 hover:bg-purple-500 p-4 rounded-xl flex items-center justify-center gap-3 font-bold transition-all border border-purple-400/20">
                        <i class="fa-solid fa-images"></i> 3. Cargar Productos & Imágenes
                    </button>
                </form>

                <div class="border-t border-gray-700 pt-4 mt-2">
                    <a href="index.php" class="block w-full bg-gray-700 hover:bg-gray-600 p-4 rounded-xl text-center font-bold transition-all">
                        <i class="fa-solid fa-arrow-left"></i> Volver al Sistema
                    </a>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
