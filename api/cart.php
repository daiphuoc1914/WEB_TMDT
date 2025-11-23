<?php
require '../inc/config.php';
require '../inc/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'msg' => 'Vui lòng đăng nhập!']);
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];

try {
    switch ($action) {
        case 'add':
            $prod_id = (int)($_POST['prod_id'] ?? 0);
            $qty = (int)($_POST['qty'] ?? 1);
            
            if ($prod_id <= 0) {
                echo json_encode(['status' => 'error', 'msg' => 'ID sản phẩm không hợp lệ!']);
                exit;
            }
            
            // Kiểm tra sản phẩm tồn tại và còn hàng
            $prod_check = $DB->prepare("SELECT quantity FROM product WHERE id = ?");
            $prod_check->execute([$prod_id]);
            $prod = $prod_check->fetch();
            if (!$prod || $prod['quantity'] < $qty) {
                echo json_encode(['status' => 'error', 'msg' => 'Sản phẩm hết hàng!']);
                exit;
            }
            
            // Kiểm tra đã có trong giỏ chưa
            $cart_check = $DB->prepare("SELECT id, prod_qty FROM carts WHERE user_id = ? AND prod_id = ?");
            $cart_check->execute([$user_id, $prod_id]);
            $existing = $cart_check->fetch();
            
            if ($existing) {
                // Update số lượng
                $new_qty = $existing['prod_qty'] + $qty;
                $DB->prepare("UPDATE carts SET prod_qty = ? WHERE id = ?")
                   ->execute([$new_qty, $existing['id']]);
            } else {
                // Insert mới
                $DB->prepare("INSERT INTO carts (user_id, prod_id, prod_qty) VALUES (?, ?, ?)")
                   ->execute([$user_id, $prod_id, $qty]);
            }
            
            echo json_encode(['status' => 'success', 'msg' => 'Thêm thành công!']);
            break;
            
        case 'update':
            $id = (int)($_POST['id'] ?? 0);  // Cart row ID
            $qty = (int)($_POST['qty'] ?? 1);
            
            if ($id <= 0 || $qty < 1) {
                echo json_encode(['status' => 'error', 'msg' => 'ID hoặc số lượng không hợp lệ!']);
                exit;
            }
            
            // Kiểm tra cart tồn tại của user
            $cart_check = $DB->prepare("SELECT prod_id, prod_qty FROM carts WHERE id = ? AND user_id = ?");
            $cart_check->execute([$id, $user_id]);
            $cart = $cart_check->fetch();
            
            if (!$cart) {
                echo json_encode(['status' => 'error', 'msg' => 'Không tìm thấy item trong giỏ!']);
                exit;
            }
            
            // Kiểm tra stock nếu qty tăng
            $stock_check = $DB->prepare("SELECT quantity FROM product WHERE id = ?");
            $stock_check->execute([$cart['prod_id']]);
            $stock = $stock_check->fetchColumn();
            if ($qty > $stock) {
                echo json_encode(['status' => 'error', 'msg' => 'Số lượng vượt quá tồn kho!']);
                exit;
            }
            
            // Update qty
            $DB->prepare("UPDATE carts SET prod_qty = ? WHERE id = ?")
               ->execute([$qty, $id]);
            
            echo json_encode(['status' => 'success', 'msg' => 'Cập nhật thành công!']);
            break;
            
        case 'remove':
            $id = (int)($_POST['id'] ?? 0);  // Cart row ID
            
            if ($id <= 0) {
                echo json_encode(['status' => 'error', 'msg' => 'ID không hợp lệ!']);
                exit;
            }
            
            // Kiểm tra cart tồn tại của user
            $cart_check = $DB->prepare("SELECT id FROM carts WHERE id = ? AND user_id = ?");
            $cart_check->execute([$id, $user_id]);
            
            if ($cart_check->rowCount() == 0) {
                echo json_encode(['status' => 'error', 'msg' => 'Không tìm thấy sản phẩm trong giỏ!']);
                exit;
            }
            
            $DB->prepare("DELETE FROM carts WHERE id = ?")
               ->execute([$id]);
            
            echo json_encode(['status' => 'success', 'msg' => 'Xóa thành công!']);
            break;
            
        case 'get':
            // Lấy tổng số lượng giỏ hàng (cho badge)
            $total_stmt = $DB->prepare("SELECT SUM(prod_qty) as total_qty FROM carts WHERE user_id = ?");
            $total_stmt->execute([$user_id]);
            $total = $total_stmt->fetchColumn() ?: 0;
            
            echo json_encode(['status' => 'success', 'total_qty' => $total]);
            break;
            
        default:
            echo json_encode(['status' => 'error', 'msg' => 'Hành động không hợp lệ!']);
    }
} catch (PDOException $e) {
    error_log('Cart API Error: ' . $e->getMessage());  // Log DB error
    echo json_encode(['status' => 'error', 'msg' => 'Lỗi cơ sở dữ liệu!']);
} catch (Exception $e) {
    error_log('Cart API Error: ' . $e->getMessage());  // Log general error
    echo json_encode(['status' => 'error', 'msg' => 'Lỗi hệ thống!']);
}
?>