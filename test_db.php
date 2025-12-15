<?php
/**
 * Test de Conexión a Base de Datos
 * Sube este archivo a tu servidor y accede a él para diagnosticar problemas
 */

// Configuración
$host = 'sql101.infinityfree.com';
$dbname = 'if0_40687916_jabon';
$username = 'if0_40687916';
$password = 'wgLejdg0EC18';
$port = '3306';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Conexión MySQL</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            margin: 0;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #333;
            margin-top: 0;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .success {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .warning {
            background: #fff3cd;
            border-left-color: #ffc107;
            color: #856404;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
            font-family: monospace;
        }
        .icon {
            font-size: 48px;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test de Conexión MySQL</h1>
        
        <div class="info-box">
            <h3 style="margin-top: 0;">📋 Información del Servidor</h3>
            <div class="info-row">
                <span class="label">Servidor Web:</span>
                <span class="value"><?php echo $_SERVER['SERVER_NAME'] ?? 'N/A'; ?></span>
            </div>
            <div class="info-row">
                <span class="label">IP del Servidor:</span>
                <span class="value"><?php echo $_SERVER['SERVER_ADDR'] ?? 'N/A'; ?></span>
            </div>
            <div class="info-row">
                <span class="label">PHP Version:</span>
                <span class="value"><?php echo phpversion(); ?></span>
            </div>
            <div class="info-row">
                <span class="label">PDO MySQL:</span>
                <span class="value"><?php echo extension_loaded('pdo_mysql') ? '✅ Disponible' : '❌ No disponible'; ?></span>
            </div>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0;">🗄️ Configuración de Base de Datos</h3>
            <div class="info-row">
                <span class="label">Host:</span>
                <span class="value"><?php echo $host; ?></span>
            </div>
            <div class="info-row">
                <span class="label">Puerto:</span>
                <span class="value"><?php echo $port; ?></span>
            </div>
            <div class="info-row">
                <span class="label">Base de Datos:</span>
                <span class="value"><?php echo $dbname; ?></span>
            </div>
            <div class="info-row">
                <span class="label">Usuario:</span>
                <span class="value"><?php echo $username; ?></span>
            </div>
        </div>

        <?php
        // Intentar conexión
        try {
            echo '<div class="info-box warning">';
            echo '<div class="icon">⏳</div>';
            echo '<p style="text-align: center; margin: 0;">Intentando conectar...</p>';
            echo '</div>';
            
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo '<div class="info-box success">';
            echo '<div class="icon">✅</div>';
            echo '<h3 style="margin-top: 0; text-align: center;">¡Conexión Exitosa!</h3>';
            echo '<p style="text-align: center; margin: 0;">La conexión a la base de datos fue exitosa.</p>';
            echo '</div>';
            
            // Obtener información adicional
            $version = $pdo->query('SELECT VERSION()')->fetchColumn();
            echo '<div class="info-box">';
            echo '<h3 style="margin-top: 0;">📊 Información de MySQL</h3>';
            echo '<div class="info-row">';
            echo '<span class="label">Versión MySQL:</span>';
            echo '<span class="value">' . $version . '</span>';
            echo '</div>';
            
            // Listar tablas
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            echo '<div class="info-row">';
            echo '<span class="label">Tablas encontradas:</span>';
            echo '<span class="value">' . count($tables) . '</span>';
            echo '</div>';
            
            if (count($tables) > 0) {
                echo '<div style="margin-top: 10px;">';
                echo '<strong>Tablas:</strong><br>';
                foreach ($tables as $table) {
                    echo '• ' . $table . '<br>';
                }
                echo '</div>';
            }
            echo '</div>';
            
        } catch (PDOException $e) {
            echo '<div class="info-box error">';
            echo '<div class="icon">❌</div>';
            echo '<h3 style="margin-top: 0; text-align: center;">Error de Conexión</h3>';
            echo '<div class="info-row">';
            echo '<span class="label">Mensaje:</span>';
            echo '<span class="value" style="color: #dc3545;">' . htmlspecialchars($e->getMessage()) . '</span>';
            echo '</div>';
            echo '<div class="info-row">';
            echo '<span class="label">Código:</span>';
            echo '<span class="value">' . $e->getCode() . '</span>';
            echo '</div>';
            echo '</div>';
            
            // Diagnóstico adicional
            echo '<div class="info-box warning">';
            echo '<h3 style="margin-top: 0;">💡 Posibles Soluciones</h3>';
            echo '<ul style="margin: 10px 0; padding-left: 20px;">';
            
            if (strpos($e->getMessage(), 'Access denied') !== false) {
                echo '<li>Verifica que el usuario y contraseña sean correctos</li>';
                echo '<li>Asegúrate de estar accediendo desde el servidor de InfinityFree</li>';
                echo '<li>Verifica que la base de datos exista en tu panel de control</li>';
            } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
                echo '<li>La base de datos no existe. Créala desde el panel de InfinityFree</li>';
                echo '<li>Verifica que el nombre de la base de datos sea correcto</li>';
            } elseif (strpos($e->getMessage(), "Can't connect") !== false || strpos($e->getMessage(), 'Connection refused') !== false) {
                echo '<li>El servidor MySQL no está respondiendo</li>';
                echo '<li>Verifica que el hostname sea correcto: sql101.infinityfree.com</li>';
                echo '<li>Contacta al soporte de InfinityFree</li>';
            } else {
                echo '<li>Verifica todas las credenciales en el panel de InfinityFree</li>';
                echo '<li>Asegúrate de que el servidor MySQL esté activo</li>';
                echo '<li>Intenta crear una nueva base de datos</li>';
            }
            
            echo '</ul>';
            echo '</div>';
        }
        ?>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
            <p style="color: #666; font-size: 14px; margin: 0;">
                <strong>Nota:</strong> Elimina este archivo después de diagnosticar el problema por seguridad.
            </p>
        </div>
    </div>
</body>
</html>
