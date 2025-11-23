<?php
require 'inc/config.php';
require 'inc/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu wishlist
$wishlist_stmt = $DB->prepare("
    SELECT w.prod_id, p.productName, p.image, p.price, p.quantity as stock_qty, c.name as cat_name
    FROM wishlist w 
    JOIN product p ON w.prod_id = p.id 
    JOIN category c ON p.catid = c.id 
    WHERE w.user_id = ?
    ORDER BY w.create_at DESC
");
$wishlist_stmt->execute([$user_id]);
$wishlist_items = $wishlist_stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;  // Optional: Tổng giá wishlist (không bắt buộc)
foreach ($wishlist_items as $item) {
    $total_price += $item['price'];
}

$page_title = "Danh Sách Yêu Thích";
include 'header.php';
?>

<!-- Banner wishlist -->
<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">Danh Sách Yêu Thích</h2>
                <p class="text-muted mb-0">Bạn có <?= count($wishlist_items) ?> sản phẩm yêu thích</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php" class="btn btn-outline-primary">Tiếp Tục Mua Sắm</a>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <?php if (count($wishlist_items) > 0): ?>
        <!-- Bảng wishlist -->
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Danh mục</th>
                                <th>Tồn kho</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wishlist_items as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($item['image']): ?>
                                            <img src="assets/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['productName']) ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: contain;">
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($item['productName']) ?></h6>
                                            <small class="text-muted">ID: <?= $item['prod_id'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= formatMoney($item['price']) ?></td>
                                <td><?= htmlspecialchars($item['cat_name']) ?></td>
                                <td>
                                    <?php if ($item['stock_qty'] > 0): ?>
                                        <span class="badge bg-success">Còn <?= $item['stock_qty'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Hết hàng</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($item['stock_qty'] > 0 && isLoggedIn()): ?>
                                        <button class="btn btn-outline-primary btn-sm add-to-cart" data-id="<?= $item['prod_id'] ?>">Giỏ hàng</button>
                                    <?php endif; ?>
                                    <button class="btn btn-danger btn-sm remove-wishlist ms-1" data-id="<?= $item['prod_id'] ?>">Xóa</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tổng giá (optional) -->
        <div class="row justify-content-end mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Tổng giá sản phẩm yêu thích</h5>
                        <h3 class="text-primary"><?= formatMoney($total_price) ?></h3>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Wishlist rỗng -->
        <div class="text-center py-5">
            <i class="fas fa-heart fa-5x text-muted mb-4"></i>
            <h4>Danh sách yêu thích của bạn đang trống</h4>
            <p class="text-muted">Hãy thêm sản phẩm yêu thích từ trang chi tiết sản phẩm.</p>
            <a href="index.php" class="btn btn-primary">Về Trang Chủ</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
// AJAX xóa wishlist và thêm giỏ từ wishlist
document.addEventListener('DOMContentLoaded', function() {
    // Xóa khỏi wishlist
    document.querySelectorAll('.remove-wishlist').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm khỏi yêu thích?')) {
                const prodId = this.dataset.id;
                fetch('api/wishlist.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=remove&prod_id=${prodId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Lỗi xóa sản phẩm!');
                    }
                });
            }
        });
    });

    // Thêm giỏ từ wishlist
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const prodId = this.dataset.id;
            fetch('api/cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=add&prod_id=${prodId}&qty=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Đã thêm vào giỏ hàng!');
                    updateCartBadge();  // Cập nhật badge navbar nếu có
                } else {
                    alert('Lỗi thêm giỏ!');
                }
            });
        });
    });
});
</script>