<?php
//require 'inc/config.php';
//require 'inc/functions.php';

$page_title = "Danh Mục";  // Tiêu đề mặc định cho trang

include 'header.php';

$is_all_categories = (!isset($_GET['id']) || $_GET['id'] === 'all');

// Nếu là chế độ xem tất cả danh mục
if ($is_all_categories) {
    // Lấy tất cả danh mục kèm số lượng sản phẩm
    $categories = $DB->query("
        SELECT c.*, 
               (SELECT COUNT(*) FROM product WHERE catid = c.id) as product_count
        FROM category c 
        ORDER BY c.id ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
    $results_count = count($categories);
    $page_title = "Tất Cả Danh Mục";
} else {
    // Chế độ xem sản phẩm theo ID danh mục
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        redirect('index.php');
    }

    $cat_id = (int)$_GET['id'];

    // Lấy thông tin danh mục
    $cat_stmt = $DB->prepare("SELECT * FROM category WHERE id = ?");
    $cat_stmt->execute([$cat_id]);
    $category = $cat_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        redirect('index.php');
    }

    // Lấy sản phẩm theo danh mục
    $products_stmt = $DB->prepare("
        SELECT p.*, c.name as cat_name 
        FROM product p 
        JOIN category c ON p.catid = c.id 
        WHERE p.catid = ?
        ORDER BY p.id DESC
    ");
    $products_stmt->execute([$cat_id]);
    $products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

    $results_count = count($products);
    $page_title = htmlspecialchars($category['name']);
}

// Include header (head + navbar + jQuery)

?>

<!-- Banner tiêu đề -->
<?php if ($is_all_categories): ?>
    <div class="bg-light py-4 border-bottom">
        <div class="container">
            <h2 class="mb-1">Danh Mục Sản Phẩm</h2>
            
        </div>
    </div>
<?php else: ?>
    <!-- Banner danh mục cụ thể -->
    <div class="bg-light py-4 border-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-1">Danh mục: <strong><?= $page_title ?></strong></h2>
                    <p class="text-muted mb-0">Tìm thấy <?= $results_count ?> sản phẩm</p>
                </div>
                <?php if (isset($category['image']) && $category['image']): ?>
                    <div class="col-md-4 text-end">
                        <img src="assets/uploads/<?= htmlspecialchars($category['image']) ?>" alt="<?= $page_title ?>" class="img-fluid" style="max-height: 100px; object-fit: cover;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="container my-5">
    <?php if ($is_all_categories): ?>
        <!-- Hiển thị tất cả danh mục (grid lớn hơn) -->
        <div class="row">
            <?php foreach ($categories as $c): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <a href="category.php?id=<?= $c['id'] ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <?php if ($c['image'] && file_exists('assets/uploads/' . $c['image'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($c['image']) ?>" class="card-img-top" style="height:200px;object-fit:cover;" alt="<?= htmlspecialchars($c['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                                    <i class="fas fa-folder-open text-muted" style="font-size: 3rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($c['name']) ?></h5>
                                <p class="text-muted small mb-0">
                                    <?= $c['product_count'] > 0 ? $c['product_count'] . ' sản phẩm' : 'Chưa có sản phẩm' ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($results_count == 0): ?>
            <div class="text-center py-5">
                <h4>Chưa có danh mục nào</h4>
                <p class="text-muted">Vui lòng liên hệ admin để thêm danh mục.</p>
                <?php if (isAdmin()): ?>
                    <a href="admin/categories.php" class="btn btn-primary">Thêm Danh Mục (Admin)</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- Hiển thị sản phẩm theo danh mục (loại bỏ thêm giỏ, ảnh clickable) -->
<?php if ($results_count > 0): ?>
    <div class="row">
        <?php foreach ($products as $p): ?>
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
<?php else: ?>
    <!-- Phần chưa có sản phẩm giữ nguyên -->
    <div class="text-center py-5">
        <h4>Danh mục "<strong><?= $page_title ?></strong>" hiện chưa có sản phẩm</h4>
        <p class="text-muted">Hãy quay lại <a href="category.php?all=1">danh sách danh mục</a> để xem các danh mục khác.</p>
        <div class="mt-4">
            <a href="category.php?all=1" class="btn btn-primary">Xem Tất Cả Danh Mục</a>
        </div>
    </div>
<?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>  <!-- Include footer (scripts + đóng body) -->