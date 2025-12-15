<?php
// JabonesPOS Smart Installer
// Versión: Auto-Detect Password

// Configuración Base
$host = 'sql101.infinityfree.com';
$user = 'if0_40687916';
$dbname = 'if0_40687916_jabon';
$port = '3306';

// LISTA DE INTELIGENCIA DE CONTRASEÑAS
// El sistema probará estas contraseñas una por una hasta conectar
$possible_passwords = [
    'wgLejdg0EC18', // Con número 1
    'wgLejdg0ECl8', // Con letra l (ele minúscula)
    'wgLejdg0ECI8', // Con letra I (i mayúscula)
    'wgLejdgOEC18', // Con O mayúscula en vez de 0
    'wgLejdgOECl8'  // Combinación O y l
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
        
        // ¡Si llegamos aquí, conectó!
        $pdo = $test_pdo;
        $connected_pass = $try_pass;
        break; // Dejar de probar
    } catch (PDOException $e) {
        continue; // Probar la siguiente
    }
}

// 2. SI NO CONECTÓ CON NINGUNA
if (!$pdo) {
    die("
    <div style='background:#1a1a1a;color:white;padding:20px;font-family:sans-serif;text-align:center;'>
        <h1 style='color:#ef4444'>❌ Error Fatal de Acceso</h1>
        <p>El sistema probó <strong>" . count($possible_passwords) . " variaciones</strong> de la contraseña y ninguna funcionó.</p>
        <p>Por favor, entra a tu panel de InfinityFree -> MySQL Databases -> Y cambia la contraseña manualmente a algo simple, ejemplo: <code>Jabones2024</code></p>
        <p style='color:#888;font-size:12px'>Error Técnico: Fin de intentos, acceso denegado.</p>
    </div>");
}

// 3. AUTO-CORREGIR CONFIG.PHP SI SE ENCONTRÓ LA CONTRASEÑA CORRECTA
$configFile = 'config/database.php';
$configContent = "<?php
/**
 * Configuración de Base de Datos - Auto-Generada
 */
\$host = '$host';
\$dbname = '$dbname';
\$username = '$user';
\$password = '$connected_pass'; // Auto-detected
\$port = '$port';

try {
    \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException \$e) {
    die(\"Error DB: \" . \$e->getMessage());
}
?>";

// Intentar actualizar el archivo config automáticamente
@file_put_contents($configFile, $configContent);


// ==========================================
// PROCESAMIENTO DE ACCIONES
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

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
    elseif ($action === 'load_demo_data') {
        try {
            // Demo Data
            $products = [
                // Líquidos
                [1, 'Detergente Líquido Premium', 'DET-LIQ-01', 'MarcaPropia', 1, 'Litro', 15.00, 8.50, 200.00, 20],
                [1, 'Detergente Bebé', 'DET-BEB-01', 'Suave', 1, 'Litro', 18.50, 10.00, 50.00, 10],
                [2, 'Suavizante Floral', 'SUA-FLO-01', 'AromaMax', 1, 'Litro', 12.00, 6.00, 150.00, 20],
                [3, 'Cloro Gel', 'CLO-GEL-01', 'Limpiex', 1, 'Litro', 8.00, 4.00, 300.00, 30],
                [3, 'Desengrasante Cítrico', 'DES-IND-01', 'PowerClean', 1, 'Galón', 45.00, 25.00, 40.00, 5],
                // Sólidos/Unidades
                [1, 'Detergente Polvo', 'DET-POL-01', 'Bio', 0, 'Kg', 22.00, 11.00, 100.00, 15],
                [5, 'Esponja Doble', 'ESP-001', 'Generic', 0, 'Unidad', 5.00, 2.00, 500.00, 50],
            ];

            $sql = "INSERT INTO products (category_id, name, sku, brand, is_liquid, display_unit, price, cost_price, stock_quantity, min_stock) VALUES (?,?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);

            foreach ($products as $p) {
                // Check if SKU exists
                $check = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
                $check->execute([$p[2]]);
                if($check->rowCount() == 0) $stmt->execute($p);
            }

            $message = "¡Datos Demo cargados con éxito!";
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
    <title>Instalador Inteligente | JabonesPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-xl w-full bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-700">
        
        <div class="bg-emerald-600 p-6 text-center">
            <h1 class="text-2xl font-bold mb-1">✅ Conexión Establecida</h1>
            <p class="text-emerald-100 text-sm">Contraseña correcta detectada y guardada</p>
        </div>

        <div class="p-8 space-y-4">
            
            <?php if($message): ?>
                <div class="p-4 rounded-lg text-center font-bold mb-4 <?php echo $messageType === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-4">
                <form method="POST">
                    <input type="hidden" name="action" value="install_structure">
                    <button class="w-full bg-blue-600 hover:bg-blue-500 p-4 rounded-xl flex items-center justify-center gap-3 font-bold transition-all">
                        <i class="fa-solid fa-database"></i> 1. Instalar Tablas
                    </button>
                </form>

                <form method="POST">
                     <input type="hidden" name="action" value="load_demo_data">
                    <button class="w-full bg-purple-600 hover:bg-purple-500 p-4 rounded-xl flex items-center justify-center gap-3 font-bold transition-all">
                        <i class="fa-solid fa-gift"></i> 2. Cargar Datos Demo
                    </button>
                </form>

                <a href="index.php" class="block w-full bg-gray-700 hover:bg-gray-600 p-4 rounded-xl text-center font-bold transition-all mt-4">
                    <i class="fa-solid fa-arrow-right"></i> Ir al Sistema
                </a>
            </div>

        </div>
    </div>
</body>
</html>
