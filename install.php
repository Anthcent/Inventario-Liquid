<?php
// JabonesPOS Installation Script
// Versión Producción - InfinityFree

// Credenciales DIRECTAS del Hosting
$host = 'sql101.infinityfree.com';
$user = 'if0_40687916';
$pass = 'wgLejdg0EC18'; // Corregido: Es un "1" (uno), no una "l" (ele)
$dbname = 'if0_40687916_jabon';
$port = '3306';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Conexión Directa
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Crear Tablas
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
        
        // Insertar Datos Iniciales
        $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO categories (name, description) VALUES 
                ('Detergentes', 'Detergentes líquidos y en polvo'),
                ('Suavizantes', 'Suavizantes de ropa'),
                ('Limpiadores', 'Limpiadores de piso y superficies'),
                ('Desengrasantes', 'Para cocina y uso industrial')
            ");
        }

        $message = "¡Instalación Exitosa en InfinityFree!";

    } catch (PDOException $e) {
        $message = "Error SQL: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instalador PRO - JabonesPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-slate-800 rounded-xl p-8 shadow-2xl border border-slate-700">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Instalador InfinityFree</h1>
            <p class="text-gray-400 text-sm mt-2">Conexión Directa a Producción</p>
        </div>

        <?php if($message): ?>
            <div class="bg-blue-600/20 text-blue-300 p-4 rounded-lg mb-4 text-center border border-blue-600/30">
                <?php echo $message; ?>
            </div>
            <a href="index.php" class="block w-full bg-emerald-600 hover:bg-emerald-500 py-3 rounded-lg text-center font-bold">Ir al Sistema</a>
        <?php else: ?>
            <form method="POST">
                <div class="bg-slate-900/50 p-4 rounded-lg mb-6 text-sm text-gray-300 space-y-2">
                    <p><strong>Host:</strong> <?php echo $host; ?></p>
                    <p><strong>Database:</strong> <?php echo $dbname; ?></p>
                    <p><strong>User:</strong> <?php echo $user; ?></p>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 py-3 rounded-lg font-bold">
                    Instalar Base de Datos
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
