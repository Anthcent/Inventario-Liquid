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
    
    // MODO DEBUG: Mostrar información detallada (temporal)
    $debugMode = true; // Cambiar a false después de solucionar
    
    if ($isLocal || $debugMode) {
        // Mostrar error completo para debugging
        echo "<div style='background: #1a1a1a; color: #fff; padding: 20px; font-family: monospace; border-left: 4px solid #ef4444;'>";
        echo "<h3 style='color: #ef4444; margin-top: 0;'>❌ Error de Conexión</h3>";
        echo "<p><strong>Entorno:</strong> " . ($isLocal ? "LOCAL" : "PRODUCCIÓN") . "</p>";
        echo "<p><strong>Servidor:</strong> $serverName</p>";
        echo "<p><strong>Host DB:</strong> $host</p>";
        echo "<p><strong>Puerto:</strong> $port</p>";
        echo "<p><strong>Base de datos:</strong> $dbname</p>";
        echo "<p><strong>Usuario:</strong> $username</p>";
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>Código:</strong> " . $e->getCode() . "</p>";
        echo "</div>";
        die();
    } else {
        // En producción sin debug, error genérico por seguridad
        error_log("DB Error: " . $e->getMessage());
        die("❌ $errorMsg. Por favor contacta al administrador.");
    }
}
?>