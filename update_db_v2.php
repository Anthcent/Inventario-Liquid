<?php
require_once 'config/database.php';

try {
    // 1. Create Categories Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 2. Add category_id and specific image management columns to products if not exists
    // We will keep 'category_id' as the FK. 
    // If 'category' (string) column exists, we can keep it as legacy or sync it.
    // For this update, let's prioritize category_id.
    
    $check = $pdo->query("SHOW COLUMNS FROM products LIKE 'category_id'");
    if ($check->rowCount() == 0) {
        $pdo->exec("ALTER TABLE products ADD COLUMN category_id INT NULL AFTER brand");
        $pdo->exec("ALTER TABLE products ADD CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL");
    }

    // Ensure image_path is there (it was in install.php but good to verify)
    // We already have image_path from install.php.
    
    echo "Base de datos actualizada: Tabla categorías creada y relacion establecida.";

} catch (PDOException $e) {
    echo "Error updating database: " . $e->getMessage();
}
?>
