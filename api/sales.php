<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        try {
            $stmt = $pdo->query("SELECT * FROM sales ORDER BY created_at DESC");
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'get_details':
        $sale_id = $_GET['id'] ?? null;
        if(!$sale_id) {
            echo json_encode(['status' => 'error', 'message' => 'ID missing']);
            exit;
        }
        try {
            $stmt = $pdo->prepare("
                SELECT si.*, p.name as product_name, p.sku 
                FROM sale_items si 
                LEFT JOIN products p ON si.product_id = p.id 
                WHERE si.sale_id = ?
            ");
            $stmt->execute([$sale_id]);
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
             echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'save': // Process Sale
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['cart'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
            exit;
        }

        $payment_method = $data['payment_method'] ?? 'cash';
        $amount_tendered = $data['amount_tendered'] ?? 0;

        try {
            $pdo->beginTransaction();

            // Validate Stock First
            foreach ($data['cart'] as $item) {
                $stmt = $pdo->prepare("SELECT stock_quantity, name FROM products WHERE id = ?");
                $stmt->execute([$item['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$product) throw new Exception("Producto no encontrado: " . $item['name']);
                if ($product['stock_quantity'] < $item['quantity_to_sell']) {
                    throw new Exception("Stock insuficiente para: " . $product['name']);
                }
            }

            // Calculate Total & Prepare Items
            $total_sale_amount = 0;
            $items_to_save = [];

            foreach ($data['cart'] as $item) {
                $product_id = $item['id'];
                $quantity_sold = $item['quantity_to_sell'];
                $subtotal = $item['subtotal'];
                
                // Deduct Stock
                $update = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
                $update->execute([$quantity_sold, $product_id]);

                $total_sale_amount += $subtotal;
                $items_to_save[] = [
                    'product_id' => $product_id,
                    'quantity' => $quantity_sold,
                    'subtotal' => $subtotal
                ];
            }

            // Create Sale Record
            $stmt = $pdo->prepare("INSERT INTO sales (total_amount, payment_method, amount_tendered) VALUES (?, ?, ?)");
            $stmt->execute([$total_sale_amount, $payment_method, $amount_tendered]);
            $sale_id = $pdo->lastInsertId();

            // Save Items
            $insert_item = $pdo->prepare("INSERT INTO sale_items (sale_id, product_id, quantity, subtotal) VALUES (?, ?, ?, ?)");
            foreach ($items_to_save as $item) {
                $insert_item->execute([$sale_id, $item['product_id'], $item['quantity'], $item['subtotal']]);
            }

            $pdo->commit();
            echo json_encode(['status' => 'success', 'sale_id' => $sale_id]);

        } catch (Exception $e) {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;
}
?>
