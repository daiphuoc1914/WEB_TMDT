<?php
// Set title trước header
$page_title = "Đơn Hàng Của Tôi";

include 'header.php';  // Load config, functions, $DB, navbar, jQuery

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của user
$orders_stmt = $DB->prepare("
    SELECT o.*, su.name_ship 
    FROM orders o 
    LEFT JOIN shipping_unit su ON o.shipping = su.id 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$orders_stmt->execute([$user_id]);
$orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý xem chi tiết đơn hàng (nếu có GET order_id)
$order_detail = null;
if (isset($_GET['order_id']) && is_numeric($_GET['order_id'])) {
    $detail_stmt = $DB->prepare("
        SELECT oi.*, p.productName, p.image, p.price as current_price 
        FROM order_items oi 
        JOIN product p ON oi.prod_id = p.id 
        WHERE oi.order_id = ? 
        ORDER BY oi.id
    ");
    $detail_stmt->execute([$_GET['order_id']]);
    $order_items = $detail_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Lấy info đơn hàng cho detail
    $order_info_stmt = $DB->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
    $order_info_stmt->execute([$_GET['order_id'], $user_id]);
    $order_detail = $order_info_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($order_detail) {
        $order_detail['items'] = $order_items;
    } else {
        redirect('my-orders.php');
    }
}

$success = $_GET['success'] ?? '';
?>

<!-- Banner đơn hàng -->
<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">Đơn Hàng Của Tôi</h2>
                <p class="text-muted mb-0">Quản lý và theo dõi đơn hàng của bạn</p>
            </div>
            <?php if ($success): ?>
                <div class="col-md-4 text-end">
                    <div class="alert alert-success d-inline-block"><?= $_GET['success'] == 1 ? 'Đặt hàng thành công!' : htmlspecialchars($success) ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container my-5">
    <?php if ($order_detail): ?>
        <!-- Chi tiết đơn hàng (nếu xem detail) -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="my-orders.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Quay Lại Danh Sách</a>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Chi Tiết Đơn Hàng #<?= $order_detail['id'] ?> - <?= $order_detail['tracking_no'] ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Thông tin giao hàng</h6>
                                <p><strong>Tên:</strong> <?= htmlspecialchars($order_detail['name']) ?></p>
                                <p><strong>Email:</strong> <?= htmlspecialchars($order_detail['email']) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($order_detail['phone']) ?></p>
                                <p><strong>Địa chỉ:</strong> <?= nl2br(htmlspecialchars($order_detail['address'])) ?></p>
                                <p><strong>Vận chuyển:</strong> <?= htmlspecialchars($order_detail['name_ship'] ?? 'Chưa xác định') ?></p>
                                <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order_detail['payment_mode']) ?></p>
                                <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order_detail['created_at'])) ?></p>
                                <p><strong>Trạng thái:</strong> 
                                    <?php 
                                    $status_text = ['Chờ xử lý', 'Đã xác nhận', 'Đang giao', 'Đã giao', 'Hoàn thành', 'Hủy'];
                                    echo '<span class="badge ' . ($order_detail['status'] == 4 ? 'bg-success' : ($order_detail['status'] == 5 ? 'bg-danger' : 'bg-warning')) . '">' . $status_text[$order_detail['status']] . '</span>';
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Tổng tiền: <?= formatMoney($order_detail['total_price']) ?></h6>
                                <?php if ($order_detail['comments']): ?>
                                    <h6>Ghi chú:</h6>
                                    <p><?= nl2br(htmlspecialchars($order_detail['comments'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Chi tiết sản phẩm -->
                        <hr class="my-4">
                        <h6>Sản phẩm trong đơn hàng</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá lúc mua</th>
                                        <th>Tổng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($order_detail['items'] as $item): 
                                        $item_total = $item['price'] * $item['qty'];
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($item['image']): ?>
                                                    <img src="assets/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['productName']) ?>" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: contain;">
                                                <?php endif; ?>
                                                <?= htmlspecialchars($item['productName']) ?>
                                            </div>
                                        </td>
                                        <td><?= $item['qty'] ?></td>
                                        <td><?= formatMoney($item['price']) ?></td>
                                        <td><?= formatMoney($item_total) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Danh sách đơn hàng -->
        <div class="row">
            <div class="col-12">
                <?php if (count($orders) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Tổng tiền</th>
                                    <th>Vận chuyển</th>
                                    <th>Thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?= $order['id'] ?> - <?= $order['tracking_no'] ?></strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td><?= formatMoney($order['total_price']) ?></td>
                                    <td><?= htmlspecialchars($order['name_ship'] ?? 'Chưa xác định') ?></td>
                                    <td><?= htmlspecialchars($order['payment_mode']) ?></td>
                                    <td>
                                        <?php 
                                        $status_text = ['Chờ xử lý', 'Đã xác nhận', 'Đang giao', 'Đã giao', 'Hoàn thành', 'Hủy'];
                                        $status_class = $order['status'] == 4 ? 'bg-success' : ($order['status'] == 5 ? 'bg-danger' : 'bg-warning');
                                        echo '<span class="badge ' . $status_class . '">' . $status_text[$order['status']] . '</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <a href="my-orders.php?order_id=<?= $order['id'] ?>" class="btn btn-primary btn-sm">Chi tiết</a>
                                        <?php if ($order['status'] == 0): ?>
                                            <a href="#" class="btn btn-warning btn-sm cancel-order" data-id="<?= $order['id'] ?>">Hủy</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-bag fa-5x text-muted mb-4"></i>
                        <h4>Bạn chưa có đơn hàng nào</h4>
                        <p class="text-muted">Hãy đặt hàng đầu tiên để theo dõi.</p>
                        <a href="index.php" class="btn btn-primary">Mua Sắm Ngay</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
// AJAX hủy đơn hàng (nếu cần)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cancel-order').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc muốn hủy đơn hàng này?')) {
                const orderId = this.dataset.id;
                // Gọi API hoặc PHP để hủy (cập nhật status=5)
                fetch('api/order.php', {  // Giả sử có api/order.php
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=cancel&order_id=${orderId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Lỗi hủy đơn hàng!');
                    }
                });
            }
        });
    });
});
</script>