<?php
// require 'inc/config.php'; // Đã include trong header.php
// require 'inc/functions.php';

// Include header (chứa head, navbar, kết nối DB)
include 'header.php';

// --- PHẦN 1: XỬ LÝ LOGIC (CONTROLLER) ---

// Khởi tạo biến mặc định
$products = [];
$categories = [];
$results_count = 0;
$page_title = "";
$category_info = null; // Biến chứa thông tin danh mục (ảnh, tên) nếu đang xem chi tiết

// Xác định chế độ xem
$is_search = isset($_GET['search']); // Chế độ tìm kiếm
// Chế độ xem tất cả danh mục (nếu không tìm kiếm VÀ (không có id HOẶC id=all))
$is_all_categories = (!$is_search && (!isset($_GET['id']) || $_GET['id'] === 'all')); 

if ($is_search) {
    // === LOGIC TÌM KIẾM ===
    $keyword = trim($_GET['search']);
    
    // Xử lý bảo mật hiển thị từ khóa
    $display_keyword = htmlspecialchars($keyword);
    $page_title = "Tìm kiếm: " . $display_keyword;

    if (empty($keyword)) {
        // Nếu ô tìm kiếm rỗng, đẩy về trang chủ hoặc thông báo
        echo "<script>alert('Vui lòng nhập từ khóa!'); window.location.href='index.php';</script>";
        exit;
    }

    // Tìm kiếm trong tên sản phẩm (dùng LIKE)
    // Join với bảng category để lấy tên danh mục hiển thị
    $stmt = $DB->prepare("
        SELECT p.*, c.name as cat_name 
        FROM product p 
        LEFT JOIN category c ON p.catid = c.id 
        WHERE p.productName LIKE ? 
        ORDER BY p.id DESC
    ");
    $stmt->execute(["%$keyword%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results_count = count($products);

} elseif ($is_all_categories) {
    // === LOGIC XEM TẤT CẢ DANH MỤC ===
    $page_title = "Tất Cả Danh Mục";
    
    // Lấy danh sách danh mục + đếm số sản phẩm trong mỗi danh mục
    $stmt = $DB->query("
        SELECT c.*, 
               (SELECT COUNT(*) FROM product WHERE catid = c.id) as product_count
        FROM category c 
        ORDER BY c.id ASC
    ");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $results_count = count($categories);

} else {
    // === LOGIC XEM SẢN PHẨM THEO DANH MỤC ===
    
    // Kiểm tra ID hợp lệ
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }

    $cat_id = (int)$_GET['id'];

    // Lấy thông tin danh mục hiện tại để hiển thị Banner
    $cat_stmt = $DB->prepare("SELECT * FROM category WHERE id = ?");
    $cat_stmt->execute([$cat_id]);
    $category_info = $cat_stmt->fetch(PDO::FETCH_ASSOC);

    // Nếu ID danh mục không tồn tại -> về trang chủ
    if (!$category_info) {
        echo "<script>window.location.href='index.php';</script>";
        exit;
    }

    $page_title = htmlspecialchars($category_info['name']);

    // Lấy danh sách sản phẩm thuộc danh mục này
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
}

?>

<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <?php if ($is_search): ?>
                    <h2 class="mb-1">Kết quả tìm kiếm: "<strong><?= $display_keyword ?></strong>"</h2>
                <?php elseif ($is_all_categories): ?>
                    <h2 class="mb-1">Danh Mục Sản Phẩm</h2>
                <?php else: ?>
                    <h2 class="mb-1">Danh mục: <strong><?= $page_title ?></strong></h2>
                <?php endif; ?>
                
                <p class="text-muted mb-0">Tìm thấy <?= $results_count ?> kết quả</p>
            </div>
            
            <?php if (!$is_search && !$is_all_categories && isset($category_info['image']) && $category_info['image']): ?>
                <div class="col-md-4 text-end">
                    <img src="assets/uploads/<?= htmlspecialchars($category_info['image']) ?>" 
                         alt="<?= $page_title ?>" 
                         class="img-fluid" 
                         style="max-height: 100px; object-fit: cover;">
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container my-5">
    
    <?php if ($is_all_categories): ?>
        <div class="row">
            <?php foreach ($categories as $c): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <a href="category.php?id=<?= $c['id'] ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-shadow">
                            <?php if ($c['image'] && file_exists('assets/uploads/' . $c['image'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($c['image']) ?>" class="card-img-top" style="height:200px;object-fit:cover;" alt="<?= htmlspecialchars($c['name']) ?>">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                                    <span class="text-muted" style="font-size: 3rem;">📁</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body text-center">
                                <h5 class="card-title text-dark"><?= htmlspecialchars($c['name']) ?></h5>
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
            </div>
        <?php endif; ?>

    <?php else: ?>
        <?php if ($results_count > 0): ?>
            <div class="row">
                <?php foreach ($products as $p): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <a href="product-detail.php?id=<?= $p['id'] ?>" class="text-decoration-none">
                            <?php if ($p['image'] && file_exists('assets/uploads/' . $p['image'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($p['image']) ?>" class="card-img-top" style="height:220px;object-fit:contain; padding: 10px;" alt="<?= htmlspecialchars($p['productName']) ?>">
                            <?php else: ?>
                                <img src="assets/images/default-product.jpg" class="card-img-top" style="height:220px;object-fit:contain;" alt="Sản phẩm mặc định">
                            <?php endif; ?>
                        </a>
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title text-truncate">
                                <a href="product-detail.php?id=<?= $p['id'] ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($p['productName']) ?>
                                </a>
                            </h6>
                            
                            <p class="text-muted small mb-2">
                                <?= isset($p['cat_name']) ? htmlspecialchars($p['cat_name']) : 'Danh mục' ?>
                            </p>
                            
                            <p class="fw-bold text-danger fs-5 mb-2">
                                <?= function_exists('formatMoney') ? formatMoney($p['price']) : number_format($p['price']).' đ' ?>
                            </p>
                            
                            <?php if ($p['quantity'] > 0): ?>
                                <span class="badge bg-success mb-2 align-self-start">Còn <?= $p['quantity'] ?> sản phẩm</span>
                            <?php else: ?>
                                <span class="badge bg-danger mb-2 align-self-start">Hết hàng</span>
                            <?php endif; ?>
                            
                            <div class="mt-auto">
                                <a href="product-detail.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-4">
                    <span style="font-size: 4rem;">🔍</span>
                </div>
                <?php if ($is_search): ?>
                    <h4>Không tìm thấy sản phẩm nào cho từ khóa "<strong><?= $display_keyword ?></strong>"</h4>
                    <p class="text-muted">Vui lòng thử lại với từ khóa khác hoặc kiểm tra chính tả.</p>
                <?php else: ?>
                    <h4>Danh mục "<strong><?= $page_title ?></strong>" hiện chưa có sản phẩm</h4>
                    <p class="text-muted">Sản phẩm đang được cập nhật.</p>
                <?php endif; ?>
                
                <div class="mt-4">
                    <a href="category.php?all=1" class="btn btn-primary">Xem Tất Cả Danh Mục</a>
                    <a href="index.php" class="btn btn-outline-secondary ms-2">Về Trang Chủ</a>
                </div>
            </div>
        <?php endif; ?>

    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>