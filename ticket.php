<?php
require_once 'config/database.php';
$id = $_GET['id'] ?? null;

if (!$id) die("ID Inválido");

$stmt = $pdo->prepare("SELECT * FROM sales WHERE id = ?");
$stmt->execute([$id]);
$sale = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$sale) die("Venta no encontrada");

$stmtItems = $pdo->prepare("
    SELECT si.*, p.name 
    FROM sale_items si 
    LEFT JOIN products p ON si.product_id = p.id 
    WHERE si.sale_id = ?
");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ticket #<?php echo $id; ?></title>
    <style>
        body { font-family: 'Courier New', monospace; font-size: 12px; max-width: 300px; margin: 0 auto; padding: 10px; }
        .header { text-align: center; margin-bottom: 10px; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        .item { display: flex; justify-content: space-between; }
        .total { font-weight: bold; text-align: right; margin-top: 10px; }
        .footer { text-align: center; margin-top: 20px; font-size: 10px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <strong>SISTEMA JABONE</strong><br>
        Venta de Insumos de Limpieza<br>
        Ticket #<?php echo str_pad($sale['id'], 6, '0', STR_PAD_LEFT); ?><br>
        <?php echo $sale['created_at']; ?>
    </div>
    
    <div class="line"></div>
    
    <?php foreach($items as $item): ?>
    <div class="item">
        <span><?php echo number_format($item['quantity'], 2); ?> x <?php echo substr($item['name'], 0, 15); ?></span>
        <span><?php echo number_format($item['subtotal'], 2); ?></span>
    </div>
    <?php endforeach; ?>
    
    <div class="line"></div>
    
    <div class="total">
        TOTAL: $ <?php echo number_format($sale['total_amount'], 2); ?>
    </div>
    
    <?php if($sale['payment_method'] == 'cash'): ?>
    <div class="item" style="margin-top:5px;">
        <span>Efectivo:</span>
        <span><?php echo number_format($sale['amount_tendered'], 2); ?></span>
    </div>
    <div class="item">
        <span>Cambio:</span>
        <span><?php echo number_format($sale['amount_tendered'] - $sale['total_amount'], 2); ?></span>
    </div>
    <?php else: ?>
    <div class="item" style="margin-top:5px;">
        <span>Método de Pago:</span>
        <span><?php echo ucfirst($sale['payment_method']); ?></span>
    </div>
    <?php endif; ?>
    
    <div class="footer">
        ¡Gracias por su compra!<br>
        Vuelva Pronto
    </div>

    <button class="no-print" onclick="window.print()" style="width:100%; padding: 10px; margin-top: 20px; cursor: pointer;">Imprimir</button>
</body>
</html>
