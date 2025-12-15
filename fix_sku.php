<?php
require_once 'config/database.php';

try {
    echo "Fixing SKU column...<br>";

    // 1. Make SKU nullable
    $pdo->exec("ALTER TABLE products MODIFY sku VARCHAR(50) NULL DEFAULT NULL");
    echo "Changed SKU to NULLable.<br>";

    // 2. Set empty strings to NULL
    $pdo->exec("UPDATE products SET sku = NULL WHERE sku = '' OR sku = ' '");
    echo "Updated empty SKUs to NULL.<br>";

    echo "Done!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
