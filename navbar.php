<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #1e40af, #3b82f6); box-shadow: 0 4px 12px rgba(0,0,0,0.15);" data-bs-theme="dark">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="index.php">
            <span style="color:#fff;">VPP</span><span style="color:#fbbf24;">Store</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link fw-semibold" href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="category.php?all=1">Danh mục</a></li>
                <li class="nav-item position-relative">
                    <a class="nav-link fw-semibold" href="cart.php">
                        Giỏ hàng
                        <?php if (isLoggedIn()): ?>
                            <?php
                            $stmt = $DB->prepare("SELECT SUM(prod_qty) as total FROM carts WHERE user_id = ?");
                            $stmt->execute([$_SESSION['user_id']]);
                            $count = $stmt->fetchColumn() ?: 0;
                            if ($count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                                    <?= $count ?>
                                    <span class="visually-hidden">sản phẩm</span>
                                </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item"><a class="nav-link fw-semibold" href="wishlist.php">Yêu thích</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if(isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                            Xin chào, <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="my-orders.php">Đơn hàng của tôi</a></li>
                            <?php if(isAdmin()): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger fw-bold" href="admin/">Quản trị Admin</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="login.php">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="register.php">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Thanh tìm kiếm (giữ nguyên, chỉ đẹp hơn) -->
<div class="bg-white border-bottom shadow-sm">
    <div class="container py-3">
        <form action="search.php" method="GET" class="d-flex">
            <input type="text" name="q" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..." required style="border-radius: 50px;">
            <button class="btn btn-primary px-4" style="border-radius: 50px;">Tìm</button>
        </form>
    </div>
</div>