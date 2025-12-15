<?php
// JabonesPOS Installation Script
// Run this file to setup the database structure.

// Detectar si estamos en servidor local o producción
$isLocal = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']) || 
           strpos($_SERVER['SERVER_NAME'], '.local') !== false ||
           strpos($_SERVER['HTTP_HOST'], 'localhost') !== false;

if ($isLocal) {
    // CONFIGURACIÓN LOCAL (XAMPP/WAMP/MAMP)
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'inventario_liquid_local';
    $port = '3306';
    $environment = 'LOCAL';
} else {
    // CONFIGURACIÓN PRODUCCIÓN (InfinityFree)
    $host = 'sql101.infinityfree.com';
    $user = 'if0_40687916';
    $pass = 'wgLejdg0EC18';
    $dbname = 'if0_40687916_jabon';
    $port = '3306';
    $environment = 'PRODUCCIÓN';
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Create Database if not exists
        $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$dbname`");
        
        // 2. Create Tables
        $queries = [
            // Categories
            "CREATE TABLE IF NOT EXISTS categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",

            // Products
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

            // Sales
            "CREATE TABLE IF NOT EXISTS sales (
                id INT AUTO_INCREMENT PRIMARY KEY,
                total_amount DECIMAL(10,2) NOT NULL,
                payment_method VARCHAR(50) DEFAULT 'cash',
                amount_tendered DECIMAL(10,2) DEFAULT 0.00,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",

            // Sale Items
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
        
        // 3. Insert Default Data (Optional)
        $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO categories (name, description) VALUES 
                ('Detergentes', 'Detergentes líquidos y en polvo'),
                ('Suavizantes', 'Suavizantes de ropa'),
                ('Limpiadores', 'Limpiadores de piso y superficies'),
                ('Desengrasantes', 'Para cocina y uso industrial')
            ");
        }

        // 4. Create Config File
        $configContent = "<?php
\$host = '$host';
\$dbname = '$dbname';
\$username = '$user';
\$password = '$pass';
\$port = '$port';

try {
    \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);
    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException \$e) {
    die(\"Error de conexión: \" . \$e->getMessage());
}
?>";
        file_put_contents('config/database.php', $configContent);

        $message = "¡Instalación completada con éxito! La base de datos y el archivo de configuración han sido actualizados.";

    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalador JabonesPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-600 rounded-xl mx-auto flex items-center justify-center mb-4 shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-wand-magic-sparkles text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">Instalador de Sistema</h1>
            <p class="text-gray-400">Configuración automática de base de datos</p>
            
            <!-- Environment Badge -->
            <div class="mt-4">
                <?php if ($isLocal): ?>
                    <span class="px-3 py-1 bg-green-500/20 border border-green-500/30 text-green-400 rounded-full text-xs font-bold">
                        <i class="fa-solid fa-laptop-code"></i> ENTORNO: LOCAL
                    </span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-purple-500/20 border border-purple-500/30 text-purple-400 rounded-full text-xs font-bold">
                        <i class="fa-solid fa-cloud"></i> ENTORNO: PRODUCCIÓN
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <?php if($message): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-4 mb-6 flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-400 mt-1"></i>
                <p class="text-emerald-400 text-sm"><?php echo $message; ?></p>
            </div>
            <a href="index.php" class="block w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl text-center transition-colors shadow-lg">
                Ir al Sistema
            </a>
        <?php else: ?>
            <form method="POST">
                <div class="space-y-4 mb-6">
                    <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                        <h3 class="font-bold text-gray-300 mb-2 text-sm uppercase tracking-wide">Se instalará:</h3>
                        <ul class="text-sm text-gray-400 space-y-2">
                            <li><i class="fa-solid fa-database w-6 text-blue-400"></i> Base de datos '<?php echo $dbname; ?>'</li>
                            <li><i class="fa-solid fa-table w-6 text-purple-400"></i> Tablas de Productos y Categorías</li>
                            <li><i class="fa-solid fa-receipt w-6 text-green-400"></i> Sistema de Ventas</li>
                            <li><i class="fa-solid fa-gears w-6 text-orange-400"></i> Configuración Inicial</li>
                        </ul>
                    </div>
                    <div class="text-xs text-center text-gray-500">
                        * Asegúrate de que XAMPP (MySQL) esté corriendo.
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-500/20 flex justify-center items-center gap-2">
                    <i class="fa-solid fa-rocket"></i> Instalar Ahora
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
