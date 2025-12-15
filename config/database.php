<?php
/**
 * Configuración de Base de Datos - Producción InfinityFree
 */

$host = 'sql101.infinityfree.com';
$dbname = 'if0_40687916_jabon';
$username = 'if0_40687916';
$password = 'wgLejdg0ECl8'; // Corregido: l (ele) en lugar de 1
$port = '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // En caso de error, mostrarlo claramente para depurar (puedes quitar esto en producción real más tarde)
    die("❌ Error de Conexión en Hosting: " . $e->getMessage());
}
?>