<?php
require_once '../config/database.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'list':
        try {
            // Join with categories to get category name
            $query = "
                SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                ORDER BY p.created_at DESC
            ";
            $stmt = $pdo->query($query);
            echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'save': 
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') die(json_encode(['status' => 'error', 'message' => 'Invalid method']));
        
        $id = $_POST['id'] ?? null;
        // Basic Fields
        $name = $_POST['name'] ?? '';
        $sku = $_POST['sku'] ?? null;
        if(empty($sku)) $sku = null;
        
        $barcode = $_POST['barcode'] ?? ''; // Optional
        $brand = $_POST['brand'] ?? '';
        $category_id = $_POST['category_id'] ?? null;
        if(empty($category_id)) $category_id = null;

        $price = $_POST['price'] ?? 0;
        $cost_price = $_POST['cost_price'] ?? 0;
        $display_unit = $_POST['display_unit'] ?? 'Litro'; // Default
        
        // Stock Logic: 'stock' param is only used for initial setup or explicit adjust logic in some UIs.
        // We will ONLY update stock here if it's a NEW product. 
        // Existing products use 'update_stock'.
        $stock = $_POST['stock'] ?? 0; 

        $min_stock = $_POST['min_stock'] ?? 10;
        $is_liquid = isset($_POST['is_liquid']) && $_POST['is_liquid'] == '1' ? 1 : 0;

        // Image Handling
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/products/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('prod_') . '.' . $ext;
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image_path = 'uploads/products/' . $filename;
            }
        }

        try {
            if ($id) {
                // Update
                $sql = "UPDATE products SET name=?, sku=?, barcode=?, brand=?, category_id=?, price=?, cost_price=?, display_unit=?, min_stock=?, is_liquid=?";
                $params = [$name, $sku, $barcode, $brand, $category_id, $price, $cost_price, $display_unit, $min_stock, $is_liquid];
                
                if ($image_path) {
                    $sql .= ", image_path=?";
                    $params[] = $image_path;
                }
                
                $sql .= " WHERE id=?";
                $params[] = $id;
                
                $msg = 'Producto actualizado correctamente';
            } else {
                // Insert
                $sql = "INSERT INTO products (name, sku, barcode, brand, category_id, price, cost_price, display_unit, stock_quantity, min_stock, is_liquid, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params = [$name, $sku, $barcode, $brand, $category_id, $price, $cost_price, $display_unit, $stock, $min_stock, $is_liquid, $image_path ?? ''];
                $msg = 'Producto registrado correctamente';
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            echo json_encode(['status' => 'success', 'message' => $msg]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'update_stock':
        $id = $_POST['id'] ?? 0;
        $amount = $_POST['amount'] ?? 0;
        try {
            $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?");
            $stmt->execute([$amount, $id]);
            echo json_encode(['status' => 'success', 'message' => 'Stock actualizado']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    case 'delete':
         $id = $_POST['id'] ?? 0;
         try {
             $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
             $stmt->execute([$id]);
             echo json_encode(['status' => 'success', 'message' => 'Producto eliminado']);
         } catch (Exception $e) {
             echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
         }
         break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Action not specified']);
}
?>
