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

switch ($action) {
    case 'cancel':
        $order_id = (int)($_POST['order_id'] ?? 0);
        
        if ($order_id <= 0) {
            echo json_encode(['status' => 'error', 'msg' => 'ID đơn hàng không hợp lệ!']);
            exit;
        }
        
        // Kiểm tra đơn hàng thuộc user và chưa hủy (status < 5)
        $order_check = $DB->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ? AND status < 5");
        $order_check->execute([$order_id, $user_id]);
        $order = $order_check->fetch(PDO::FETCH_ASSOC);
        
        if (!$order) {
            echo json_encode(['status' => 'error', 'msg' => 'Không tìm thấy đơn hàng hoặc đã hủy!']);
            exit;
        }
        
        // Bắt đầu transaction để đảm bảo atomicity
        try {
            $DB->beginTransaction();
            
            // Khôi phục stock từ order_items trước khi xóa
            $items_stmt = $DB->prepare("SELECT prod_id, qty FROM order_items WHERE order_id = ?");
            $items_stmt->execute([$order_id]);
            $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($items as $item) {
                $DB->prepare("UPDATE product SET quantity = quantity + ? WHERE id = ?")
                   ->execute([$item['qty'], $item['prod_id']]);
            }
            
            // Xóa chi tiết đơn hàng
            $DB->prepare("DELETE FROM order_items WHERE order_id = ?")
               ->execute([$order_id]);
            
            // Xóa đơn hàng chính
            $DB->prepare("DELETE FROM orders WHERE id = ?")
               ->execute([$order_id]);
            
            $DB->commit();
            echo json_encode(['status' => 'success', 'msg' => 'Đã hủy và xóa đơn hàng thành công! Stock đã được khôi phục.']);
        } catch (Exception $e) {
            $DB->rollBack();
            echo json_encode(['status' => 'error', 'msg' => 'Lỗi hệ thống: ' . $e->getMessage()]);
        }
        break;
        
    case 'get':
        // Lấy đơn hàng của user (tương tự my-orders.php)
        $stmt = $DB->prepare("
            SELECT o.*, su.name_ship 
            FROM orders o 
            LEFT JOIN shipping_unit su ON o.shipping = su.id 
            WHERE o.user_id = ? 
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$user_id]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'orders' => $orders]);
        break;
        
    default:
        echo json_encode(['status' => 'error', 'msg' => 'Hành động không hợp lệ!']);
}
?>