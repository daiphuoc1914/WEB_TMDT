<?php
require 'inc/config.php';
require 'inc/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Lấy dữ liệu giỏ hàng
$cart_stmt = $DB->prepare("
    SELECT c.id, c.prod_id, c.prod_qty, p.productName, p.image, p.price, p.quantity as stock_qty
    FROM carts c 
    JOIN product p ON c.prod_id = p.id 
    WHERE c.user_id = ?
    ORDER BY c.id DESC
");
$cart_stmt->execute([$user_id]);
$cart_items = $cart_stmt->fetchAll(PDO::FETCH_ASSOC);

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['prod_qty'];
}

// Lấy danh sách shipping units
$shipping_stmt = $DB->query("SELECT * FROM shipping_unit WHERE status = 1");
$shipping_units = $shipping_stmt->fetchAll(PDO::FETCH_ASSOC);

$page_title = "Giỏ Hàng Của Tôi";
include 'header.php';
?>

<!-- Banner giỏ hàng -->
<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">Giỏ Hàng Của Tôi</h2>
                <p class="text-muted mb-0">Bạn có <?= count($cart_items) ?> sản phẩm trong giỏ</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php" class="btn btn-outline-primary">Tiếp Tục Mua Sắm</a>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <?php if (count($cart_items) > 0): ?>
        <!-- Bảng giỏ hàng -->
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart_items as $item): 
                                $item_total = $item['price'] * $item['prod_qty'];
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($item['image']): ?>
                                            <img src="assets/uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['productName']) ?>" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: contain;">
                                        <?php endif; ?>
                                        <div>
                                            <h6 class="mb-1"><?= htmlspecialchars($item['productName']) ?></h6>
                                            <small class="text-muted">Còn <?= $item['stock_qty'] ?> trong kho</small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= formatMoney($item['price']) ?></td>
                                <td>
                                    <div class="input-group input-group-sm" style="width: 150px;">
                                        <button class="btn btn-outline-secondary decrease-qty" data-id="<?= $item['id'] ?>">-</button>
                                        <input type="number" class="form-control text-center qty-input" data-cart-id="<?= $item['id'] ?>" value="<?= $item['prod_qty'] ?>" min="1" max="<?= $item['stock_qty'] ?>" readonly>
                                        <button class="btn btn-outline-secondary increase-qty" data-id="<?= $item['id'] ?>">+</button>
                                    </div>
                                </td>
                                <td><?= formatMoney($item_total) ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm remove-cart" data-id="<?= $item['id'] ?>">Xóa</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tổng kết -->
        <div class="row justify-content-end mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng Kết</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Tạm tính:</span>
                            <span id="subtotal"><?= formatMoney($total_price) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển:</span>
                            <span id="shipping-fee">Chọn đơn vị vận chuyển</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Tổng cộng:</span>
                            <span id="grand-total" class="text-danger"><?= formatMoney($total_price) ?></span>
                        </div>
                        <button class="btn btn-success w-100 mt-3" onclick="window.location.href='checkout.php'">Tiến Hành Thanh Toán</button>
                    </div>
                </div>
            </div>
        </div>

    <?php else: ?>
    <div class="empty-cart-section text-center py-5">  <!-- Thêm class này -->
        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
        <h4>Giỏ hàng của bạn đang trống</h4>
        <p class="text-muted">Hãy thêm sản phẩm yêu thích vào giỏ hàng để bắt đầu mua sắm.</p>
        <a href="index.php" class="btn btn-primary">Về Trang Chủ Mua Sắm</a>
    </div>
<?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
// AJAX xóa cart và tăng/giảm số lượng (biến mất ngay lập tức)
document.addEventListener('DOMContentLoaded', function() {
    // Xóa khỏi cart (cập nhật tổng và check empty sau success)
    document.querySelectorAll('.remove-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm khỏi giỏ hàng?')) {
                const cartId = this.dataset.id;
                const row = this.closest('tr');
                const tableBody = document.querySelector('tbody');
                const tableContainer = tableBody.closest('.table-responsive');
                const emptySection = document.querySelector('.empty-cart-section');  // Class cho phần rỗng
                
                console.log('Xóa cart - Cart ID:', cartId);
                
                fetch('api/cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=remove&id=${cartId}`
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    console.log('API response xóa:', data);
                    
                    if (data.status === 'success') {
                        // Fade out và xóa hàng
                        if (row) {
                            row.style.transition = 'opacity 0.3s';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                                // Check nếu còn hàng không
                                if (tableBody.children.length === 0) {
                                    showEmptyCart();  // Chuyển view empty
                                } else {
                                    updateCartTotal();  // Tính lại tổng
                                }
                                updateCartBadge();  // Cập nhật badge navbar
                            }, 300);
                        }
                        
                        alert('Đã xóa khỏi giỏ hàng!');
                    } else {
                        alert('Lỗi xóa: ' + (data.msg || 'Không xác định'));
                    }
                })
                .catch(error => {
                    console.error('Xóa error:', error);
                    alert('Lỗi kết nối xóa!');
                });
            }
        });
    });

    // ... Phần tăng/giảm qty giữ nguyên từ code trước (đã work) ...
    // (Để ngắn gọn, copy phần increase/decrease/updateQty/updateCartTotal/updateGrandTotal/updateCartBadge/formatMoneyJS từ response trước)

    // Hàm hiển thị giỏ rỗng (ẩn bảng, hiện empty div)
    function showEmptyCart() {
        const tableContainer = document.querySelector('.table-responsive');
        const emptySection = document.querySelector('.empty-cart-section') || createEmptySection();  // Tạo nếu chưa có
        
        if (tableContainer) tableContainer.style.display = 'none';
        if (emptySection) emptySection.style.display = 'block';
        
        // Ẩn card tổng kết
        const totalCard = document.querySelector('.col-md-4 .card');
        if (totalCard) totalCard.style.display = 'none';
        
        console.log('Giỏ hàng rỗng - Chuyển view empty');
    }

    // Hàm tạo phần empty nếu chưa có (thêm vào HTML nếu cần)
    function createEmptySection() {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'empty-cart-section text-center py-5';
        emptyDiv.innerHTML = `
            <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
            <h4>Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted">Hãy thêm sản phẩm yêu thích vào giỏ hàng để bắt đầu mua sắm.</p>
            <a href="index.php" class="btn btn-primary">Về Trang Chủ Mua Sắm</a>
        `;
        document.querySelector('.container.my-5').appendChild(emptyDiv);
        return emptyDiv;
    }

    // Tự động cập nhật khi load
    updateCartTotal();
    updateCartBadge();
    updateGrandTotal();
});
</script>
</body>
</html>