<?php
// Database Update Script v4
// This script adds missing columns to existing tables

require_once 'config/database.php';

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Check and add payment_method column to sales table
        $stmt = $pdo->query("SHOW COLUMNS FROM sales LIKE 'payment_method'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE sales ADD COLUMN payment_method VARCHAR(50) DEFAULT 'cash' AFTER total_amount");
            $message .= "✓ Campo 'payment_method' agregado a tabla 'sales'<br>";
        } else {
            $message .= "✓ Campo 'payment_method' ya existe en tabla 'sales'<br>";
        }

        // Check and add amount_tendered column to sales table
        $stmt = $pdo->query("SHOW COLUMNS FROM sales LIKE 'amount_tendered'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE sales ADD COLUMN amount_tendered DECIMAL(10,2) DEFAULT 0.00 AFTER payment_method");
            $message .= "✓ Campo 'amount_tendered' agregado a tabla 'sales'<br>";
        } else {
            $message .= "✓ Campo 'amount_tendered' ya existe en tabla 'sales'<br>";
        }

        // Verify all tables exist
        $tables = ['categories', 'products', 'sales', 'sale_items'];
        foreach ($tables as $table) {
            $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
            if ($stmt->rowCount() > 0) {
                $message .= "✓ Tabla '$table' existe<br>";
            } else {
                $errors[] = "✗ Tabla '$table' NO existe - ejecuta install.php primero";
            }
        }

        if (empty($errors)) {
            $message = "<div class='text-emerald-400'>¡Actualización completada con éxito!<br><br>" . $message . "</div>";
        } else {
            $message = "<div class='text-red-400'>" . implode('<br>', $errors) . "</div><br>" . $message;
        }

    } catch (Exception $e) {
        $message = "<div class='text-red-400'>Error: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Base de Datos v4</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-gray-800 rounded-2xl shadow-2xl p-8 border border-gray-700">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-purple-600 rounded-xl mx-auto flex items-center justify-center mb-4 shadow-lg shadow-purple-500/20">
                <i class="fa-solid fa-database text-2xl text-white"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">Actualizar Base de Datos</h1>
            <p class="text-gray-400">Versión 4 - Corrección de campos faltantes</p>
        </div>

        <?php if($message): ?>
            <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-6 mb-6">
                <?php echo $message; ?>
            </div>
            <a href="index.php" class="block w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 rounded-xl text-center transition-colors shadow-lg">
                Ir al Sistema
            </a>
        <?php else: ?>
            <form method="POST">
                <div class="space-y-4 mb-6">
                    <div class="bg-gray-900/50 p-6 rounded-xl border border-gray-700">
                        <h3 class="font-bold text-gray-300 mb-4 text-sm uppercase tracking-wide">Se actualizará:</h3>
                        <ul class="text-sm text-gray-400 space-y-3">
                            <li><i class="fa-solid fa-plus-circle w-6 text-emerald-400"></i> Agregar campo 'payment_method' a tabla 'sales'</li>
                            <li><i class="fa-solid fa-plus-circle w-6 text-emerald-400"></i> Agregar campo 'amount_tendered' a tabla 'sales'</li>
                            <li><i class="fa-solid fa-check-circle w-6 text-blue-400"></i> Verificar integridad de todas las tablas</li>
                        </ul>
                    </div>
                    
                    <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-exclamation-triangle text-yellow-400 mt-1"></i>
                            <div class="text-yellow-400 text-sm">
                                <strong>Importante:</strong> Este script es seguro y no eliminará datos existentes. Solo agregará campos faltantes.
                            </div>
                        </div>
                    </div>

                    <div class="text-xs text-center text-gray-500">
                        * Asegúrate de tener una copia de seguridad antes de continuar.
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-purple-500/20 flex justify-center items-center gap-2">
                    <i class="fa-solid fa-arrow-up"></i> Actualizar Ahora
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
