<?php
require_once 'config/database.php';

try {
    // Add columns if they don't exist
    $columns = [
        "ADD COLUMN sku VARCHAR(50) UNIQUE AFTER id",
        "ADD COLUMN barcode VARCHAR(100) AFTER sku",
        "ADD COLUMN brand VARCHAR(100) AFTER name",
        "ADD COLUMN category VARCHAR(100) AFTER brand",
        "ADD COLUMN cost_price DECIMAL(10, 2) AFTER price", /* Price needed to buy */
        "ADD COLUMN min_stock DECIMAL(10, 4) DEFAULT 10.00 AFTER stock_quantity",
        "ADD COLUMN is_liquid BOOLEAN DEFAULT 0 AFTER display_unit"
    ];

    foreach ($columns as $col) {
        try {
            $pdo->exec("ALTER TABLE products $col");
        } catch (PDOException $e) {
            // Ignore if column already exists
        }
    }

    echo "Base de datos actualizada con nuevos campos (SKU, Barcode, Marca, Categoría, Costo, Stock Mínimo, Es Líquido).";

} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>
