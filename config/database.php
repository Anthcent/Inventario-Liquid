<?php
$host = 'sql101.infinityfree.com';
$dbname = 'if0_40687916_jabon';
$username = 'if0_40687916';
$password = 'wgLejdg0EC18';
$port = '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>