<?php
/**
 * Configuración de Base de Datos - Producción InfinityFree
 */

$host = 'sql101.infinityfree.com';
$dbname = 'if0_40687916_jabon';
$username = 'if0_40687916';
$password = 'wgLejdg0ECl8'; // PASSWORD CORREGIDO: Termina en 18 (uno-ocho)
$port = '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Error simple
    die("Error de conexión (DB): " . $e->getMessage());
}
?>