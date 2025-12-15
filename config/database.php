<?php
/**
 * Configuración de Base de Datos - Dual Environment
 * Detecta automáticamente si estás en local o en producción
 */

// Detectar si estamos en servidor local o producción
// Mejorado para detectar correctamente InfinityFree
$serverName = $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost';

$isLocal = (
    in_array($serverName, ['localhost', '127.0.0.1']) || 
    strpos($serverName, '.local') !== false ||
    strpos($serverName, 'localhost') !== false ||
    // Si el servidor es una IP privada (192.168.x.x, 10.x.x.x)
    (preg_match('/^(192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\.)/i', $serverName))
);

// Si contiene dominios de hosting gratuito, forzar producción
if (strpos($serverName, '.42web.io') !== false || 
    strpos($serverName, '.infinityfreeapp.com') !== false ||
    strpos($serverName, '.rf.gd') !== false) {
    $isLocal = false;
}

if ($isLocal) {
    // ========================================
    // CONFIGURACIÓN LOCAL (XAMPP/WAMP/MAMP)
    // ========================================
    $host = 'localhost';
    $dbname = 'inventario_liquid_local';
    $username = 'root';
    $password = '';
    $port = '3306';
    
    echo "<!-- Modo: DESARROLLO LOCAL -->\n";
    
} else {
    // ========================================
    // CONFIGURACIÓN PRODUCCIÓN (InfinityFree)
    // ========================================
    $host = 'sql101.infinityfree.com';
    $dbname = 'if0_40687916_jabon';
    $username = 'if0_40687916';
    $password = 'wgLejdg0EC18';
    $port = '3306';
    
    echo "<!-- Modo: PRODUCCIÓN -->\n";
}

// Conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Opcional: Mostrar mensaje de éxito en desarrollo
    if ($isLocal && isset($_GET['test_db'])) {
        echo "✅ Conexión exitosa a: " . ($isLocal ? "LOCAL" : "PRODUCCIÓN") . "<br>";
        echo "Base de datos: $dbname<br>";
        echo "Host: $host<br>";
    }
    
} catch (PDOException $e) {
    // Mensaje de error más descriptivo
    $errorMsg = "Error de conexión a base de datos";
    
    if ($isLocal) {
        // En local, mostrar error completo para debugging
        die("❌ $errorMsg (LOCAL): " . $e->getMessage());
    } else {
        // En producción, error genérico por seguridad
        error_log("DB Error: " . $e->getMessage());
        die("❌ $errorMsg. Por favor contacta al administrador.");
    }
}
?>