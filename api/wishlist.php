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
            
            if ($prod_id <= 0) {
                echo json_encode(['status' => 'error', 'msg' => 'ID sản phẩm không hợp lệ!']);
                exit;
            }
            
            // Kiểm tra sản phẩm tồn tại
            $prod_check = $DB->prepare("SELECT id FROM product WHERE id = ?");
            $prod_check->execute([$prod_id]);
            if (!$prod_check->fetch()) {
                echo json_encode(['status' => 'error', 'msg' => 'Sản phẩm không tồn tại!']);
                exit;
            }
            
            // Kiểm tra đã có trong wishlist chưa
            $wish_check = $DB->prepare("SELECT id FROM wishlist WHERE user_id = ? AND prod_id = ?");
            $wish_check->execute([$user_id, $prod_id]);
            
            if ($wish_check->rowCount() > 0) {
                echo json_encode(['status' => 'error', 'msg' => 'Sản phẩm đã có trong yêu thích!']);
            } else {
                $DB->prepare("INSERT INTO wishlist (user_id, prod_id) VALUES (?, ?)")
                   ->execute([$user_id, $prod_id]);
                echo json_encode(['status' => 'success', 'msg' => 'Thêm yêu thích thành công!']);
            }
            break;
            
        case 'remove':
            $prod_id = (int)($_POST['prod_id'] ?? 0);
            
            if ($prod_id <= 0) {
                echo json_encode(['status' => 'error', 'msg' => 'ID sản phẩm không hợp lệ!']);
                exit;
            }
            
            // Kiểm tra tồn tại trước khi xóa (optional, để debug)
            $check_stmt = $DB->prepare("SELECT id FROM wishlist WHERE user_id = ? AND prod_id = ?");
            $check_stmt->execute([$user_id, $prod_id]);
            if ($check_stmt->rowCount() == 0) {
                echo json_encode(['status' => 'error', 'msg' => 'Không tìm thấy sản phẩm trong yêu thích!']);
                exit;
            }
            
            $DB->prepare("DELETE FROM wishlist WHERE user_id = ? AND prod_id = ?")
               ->execute([$user_id, $prod_id]);
            
            echo json_encode(['status' => 'success', 'msg' => 'Xóa thành công!']);
            break;
            
        case 'get':
            $stmt = $DB->prepare("
                SELECT w.prod_id, p.productName, p.price 
                FROM wishlist w 
                JOIN product p ON w.prod_id = p.id 
                WHERE w.user_id = ?
            ");
            $stmt->execute([$user_id]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = count($items);
            
            echo json_encode(['status' => 'success', 'items' => $items, 'total' => $total]);
            break;
            
        default:
            echo json_encode(['status' => 'error', 'msg' => 'Hành động không hợp lệ!']);
    }
} catch (PDOException $e) {
    // Log error DB (xem error_log Apache nếu cần)
    error_log('Wishlist API Error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'msg' => 'Lỗi cơ sở dữ liệu!']);
} catch (Exception $e) {
    error_log('Wishlist API Error: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'msg' => 'Lỗi hệ thống!']);
}
?>