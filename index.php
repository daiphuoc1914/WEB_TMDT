<?php
// Set tiêu đề trang
$page_title = "Trang Chủ";

// Include header (head + navbar + jQuery)
include 'header.php';
?>

<!-- Slider banner - ĐÃ CHỈNH MÀU ĐẸP HƠN -->
<div class="text-white text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); padding: 100px 20px; min-height: 400px;">
    <div class="container position-relative z-3">
        <h1 class="display-4 fw-bold mb-3">VPP Store - Văn Phòng Phẩm Chất Lượng Cao</h1>
        <p class="lead fs-3 mb-4">Giao hàng nhanh toàn quốc</p>
        <a href="category.php?all=1" class="btn btn-warning btn-lg px-5 py-3 fw-bold shadow-lg">
            Mua Sắm Ngay
        </a>
    </div>
    <!-- Hiệu ứng sóng nhẹ (tùy chọn đẹp) -->
    <div class="position-absolute bottom-0 start-0 w-100">
        <svg viewBox="0 0 1440 320" class="w-100" style="transform: translateY(50%);">
            <path fill="#fff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,176C1248,192,1344,192,1392,192L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</div>

<div class="container my-5">
    <!-- Hiển thị message nếu có (từ logout hoặc success) -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <!-- Danh mục -->
    

    <!-- Sản phẩm Trending (loại bỏ thêm giỏ, ảnh clickable) -->
<h3 class="mb-4 mt-5">Sản Phẩm Nổi Bật</h3>
<div class="row">
    <?php
    $products = $DB->query("SELECT p.*, c.name as cat_name FROM product p JOIN category c ON p.catid=c.id WHERE p.trending=1 ORDER BY p.id DESC LIMIT 12")->fetchAll();
    foreach($products as $p):
    ?>
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <a href="product-detail.php?id=<?= $p['id'] ?>" class="text-decoration-none">  <!-- Ảnh clickable -->
                <?php if ($p['image'] && file_exists('assets/uploads/' . $p['image'])): ?>
                    <img src="assets/uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" style="height:220px;object-fit:contain;" alt="<?= htmlspecialchars($p['productName']) ?>">
                <?php else: ?>
                    <img src="assets/images/default-product.jpg" class="card-img-top" style="height:220px;object-fit:contain;" alt="Sản phẩm mặc định">
                <?php endif; ?>
            </a>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title"><?= htmlspecialchars($p['productName']) ?></h6>
                <p class="text-muted small"><?= htmlspecialchars($p['cat_name']) ?></p>
                <p class="fw-bold text-danger"><?= formatMoney($p['price']) ?></p>
                <?php if ($p['quantity'] > 0): ?>
                    <span class="badge bg-success mb-2">Còn <?= $p['quantity'] ?> sản phẩm</span>
                <?php else: ?>
                    <span class="badge bg-danger mb-2">Hết hàng</span>
                <?php endif; ?>
                <div class="mt-auto">
                    <a href="product-detail.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm w-100">Xem chi tiết</a>  <!-- Giữ nút xem chi tiết -->
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</div>

<!-- Script chung (cuối body) -->
<?php include('footer.php'); ?>