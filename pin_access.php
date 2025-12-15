<?php
session_start();

$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = $_POST['pin'] ?? '';
    // Hardcoded PIN as requested
    if ($pin === '000000') {
        $_SESSION['admin_unlocked'] = true;
        header("Location: " . urldecode($redirect));
        exit;
    } else {
        $error = 'PIN Incorrecto';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Restringido - JabonesPOS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex items-center justify-center font-sans">

    <div class="glass p-8 rounded-2xl w-full max-w-sm text-center shadow-2xl mx-4">
        <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner relative">
            <i class="fa-solid fa-lock text-3xl text-gray-400"></i>
            <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full animate-pulse border-2 border-gray-900"></div>
        </div>

        <h1 class="text-2xl font-bold text-white mb-2">Acceso Restringido</h1>
        <p class="text-gray-400 text-sm mb-6">Ingresa el PIN de seguridad para acceder a este módulo.</p>

        <?php if($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm py-2 px-4 rounded-lg mb-4">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4" x-data="{ pin: '' }">
            <div class="relative">
                <input type="password" name="pin" maxlength="6" autofocus required
                    class="w-full bg-black/30 border border-gray-700 rounded-xl py-3 px-4 text-center text-white text-2xl tracking-[0.5em] focus:outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-500 transition-all font-mono placeholder-gray-600"
                    placeholder="••••••">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                <i class="fa-solid fa-unlock"></i> Desbloquear
            </button>
        </form>

        <div class="mt-6 border-t border-white/5 pt-4">
            <a href="index.php" class="text-gray-500 hover:text-white text-sm transition-colors flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Volver al Punto de Venta
            </a>
        </div>
    </div>

</body>
</html>
