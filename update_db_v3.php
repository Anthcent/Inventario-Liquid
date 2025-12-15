<?php
require_once 'config/database.php';

try {
    // Add payment_method and amount_tendered to sales
    $check = $pdo->query("SHOW COLUMNS FROM sales LIKE 'payment_method'");
    if ($check->rowCount() == 0) {
        $pdo->exec("ALTER TABLE sales ADD COLUMN payment_method VARCHAR(20) DEFAULT 'cash'");
        $pdo->exec("ALTER TABLE sales ADD COLUMN amount_tendered DECIMAL(10,2) DEFAULT 0.00");
    }

    echo "Base de datos actualizada v3: Columnas de pago agregadas.";

} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>
