<?php
require 'inc/config.php';
require 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$prod_id = (int)$_GET['id'];

// Lấy thông tin sản phẩm
$prod_stmt = $DB->prepare("
    SELECT p.*, c.name as cat_name 
    FROM product p 
    JOIN category c ON p.catid = c.id 
    WHERE p.id = ?
");
$prod_stmt->execute([$prod_id]);
$product = $prod_stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    redirect('index.php');
}

$page_title = htmlspecialchars($product['productName']);
include 'header.php';
?>

<!-- Banner sản phẩm -->
<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="category.php?id=<?= $product['catid'] ?>"><?= htmlspecialchars($product['cat_name']) ?></a></li>
                        <li class="breadcrumb-item active"><?= htmlspecialchars($product['productName']) ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-5">
            <?php if ($product['image'] && file_exists('assets/uploads/' . $product['image'])): ?>
                <img src="assets/uploads/<?= htmlspecialchars($product['image']) ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['productName']) ?>" style="max-height: 500px; object-fit: contain;">
            <?php else: ?>
                <img src="assets/images/default-product.jpg" class="img-fluid rounded shadow-sm" alt="Sản phẩm mặc định" style="max-height: 500px; object-fit: contain;">
            <?php endif; ?>
        </div>
        
        <!-- Thông tin sản phẩm -->
        <div class="col-md-7">
            <h1 class="mb-3"><?= htmlspecialchars($product['productName']) ?></h1>
            <p class="text-muted mb-3"><?= htmlspecialchars($product['cat_name']) ?></p>
            
            <h3 class="text-danger mb-3"><?= formatMoney($product['price']) ?></h3>
            
            <?php if ($product['quantity'] > 0): ?>
                <p class="text-success mb-3">Còn <?= $product['quantity'] ?> sản phẩm trong kho</p>
                <div class="mb-4">
                    <label for="qty" class="form-label">Số lượng:</label>
                    <input type="number" id="qty" class="form-control w-25 d-inline" value="1" min="1" max="<?= $product['quantity'] ?>">
                </div>
                <!-- Luôn hiển thị hai nút, JS sẽ xử lý redirect nếu chưa login -->
                <button class="btn btn-success btn-lg add-to-cart" data-id="<?= $product['id'] ?>">Thêm Vào Giỏ Hàng</button>
                <button class="btn btn-outline-danger btn-lg ms-2 add-to-wishlist" data-id="<?= $product['id'] ?>">Thêm Vào Yêu Thích</button>
            <?php else: ?>
                <p class="text-danger mb-3">Sản phẩm tạm thời hết hàng</p>
                <button class="btn btn-outline-secondary btn-lg" disabled>Thông Báo Khi Có Hàng</button>
            <?php endif; ?>
            
            <hr class="my-4">
            
            <!-- Mô tả sản phẩm -->
            <h4>Mô Tả Sản Phẩm</h4>
            <div class="mb-4">
                <?= $product['product_desc'] ? nl2br(htmlspecialchars($product['product_desc'])) : '<p class="text-muted">Chưa có mô tả chi tiết.</p>' ?>
            </div>
        </div>
    </div>
    
    <!-- Sản phẩm liên quan (tùy chọn) -->
    <div class="row mt-5">
        <h4 class="mb-4">Sản Phẩm Liên Quan</h4>
        <?php
        $related_stmt = $DB->prepare("
            SELECT p.*, c.name as cat_name 
            FROM product p 
            JOIN category c ON p.catid = c.id 
            WHERE p.catid = ? AND p.id != ? 
            ORDER BY RAND() LIMIT 4
        ");
        $related_stmt->execute([$product['catid'], $prod_id]);
        $related = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($related as $r): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <?php if ($r['image']): ?>
                        <img src="assets/uploads/<?= htmlspecialchars($r['image']) ?>" class="card-img-top" style="height:200px;object-fit:contain;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h6><?= htmlspecialchars($r['productName']) ?></h6>
                        <p class="fw-bold text-danger"><?= formatMoney($r['price']) ?></p>
                        <a href="product-detail.php?id=<?= $r['id'] ?>" class="btn btn-primary btn-sm">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
// Hàm JS check login (từ main.js)
function isLoggedIn() {
    return <?= json_encode(isLoggedIn()) ?>;
}

// AJAX thêm giỏ từ chi tiết sản phẩm (kiểm tra login trước)
$('.add-to-cart').click(function() {
    if (!isLoggedIn()) {
        // Redirect login với return URL
        const returnUrl = encodeURIComponent(window.location.href);
        window.location.href = 'login.php?return=' + returnUrl;
        return;
    }
    
    var qty = $('#qty').val() || 1;
    $.post('api/cart.php', { action: 'add', prod_id: $(this).data('id'), qty: qty }, function(res) {
        if (res.status === 'success') {
            alert('Thêm thành công ' + qty + ' sản phẩm!');
            updateCartBadge();  // Cập nhật badge nếu có
        } else {
            alert('Lỗi: ' + res.msg);
        }
    }, 'json');
});

// Thêm wishlist (tương tự)
$('.add-to-wishlist').click(function() {
    if (!isLoggedIn()) {
        // Redirect login với return URL
        const returnUrl = encodeURIComponent(window.location.href);
        window.location.href = 'login.php?return=' + returnUrl;
        return;
    }
    
    $.post('api/wishlist.php', { action: 'add', prod_id: $(this).data('id') }, function(res) {
        if (res.status === 'success') {
            alert('Đã thêm vào yêu thích!');
            // Optional: Thay đổi icon nút thành filled heart
            $(this).html('<i class="fas fa-heart"></i> Đã Yêu Thích');
        } else {
            alert('Lỗi: ' + res.msg);
        }
    }, 'json').fail(function() {
        alert('Lỗi kết nối!');
    });
});
</script>