<?php
// Set title trước header
$page_title = "Thanh Toán";

include 'header.php';  // Load config, functions, $DB, navbar, jQuery

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Prefill thông tin user từ DB
$user_stmt = $DB->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$user_stmt->execute([$user_id]);
$user_data = $user_stmt->fetch(PDO::FETCH_ASSOC) ?: ['name' => '', 'email' => '', 'phone' => ''];

// Lấy giỏ hàng để tính tổng tạm
$cart_stmt = $DB->prepare("SELECT SUM(c.prod_qty * p.price) as subtotal FROM carts c JOIN product p ON c.prod_id = p.id WHERE c.user_id = ?");
$cart_stmt->execute([$user_id]);
$subtotal = $cart_stmt->fetchColumn() ?: 0;

// Lấy shipping units
$shipping_stmt = $DB->query("SELECT * FROM shipping_unit WHERE status = 1 ORDER BY price ASC");
$shipping_units = $shipping_stmt->fetchAll(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $shipping_id = (int)$_POST['shipping'];
    $payment_mode = trim($_POST['payment_mode']);

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = "Vui lòng điền đầy đủ thông tin!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ!";
    } else {
        // Tính phí ship an toàn (prepare) + miễn phí nếu > 300k
        $ship_stmt = $DB->prepare("SELECT price FROM shipping_unit WHERE id = ?");
        $ship_stmt->execute([$shipping_id]);
        $ship_price = $ship_stmt->fetchColumn() ?: 0;
        if ($subtotal >= 300000) {
            $ship_price = 0;  // Miễn phí ship cho đơn lớn
        }

        $total_price = $subtotal + $ship_price;

        // Tạo tracking_no
        $tracking_no = 'VPP' . time() . rand(10, 99);

        // Tạo đơn hàng
        $order_stmt = $DB->prepare("
            INSERT INTO orders (tracking_no, user_id, name, email, phone, address, total_price, payment_mode, shipping, status, comments)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?)
        ");
        $order_stmt->execute([
            $tracking_no, $user_id, $name, $email, $phone, $address, $total_price, $payment_mode, $shipping_id, trim($_POST['comments'] ?? '')
        ]);
        $order_id = $DB->lastInsertId();

        // Lấy cart items để thêm order_items và giảm stock
        $items_stmt = $DB->prepare("SELECT c.prod_id, c.prod_qty, p.price FROM carts c JOIN product p ON c.prod_id = p.id WHERE c.user_id = ?");
        $items_stmt->execute([$user_id]);
        $cart_items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cart_items as $item) {
            // Thêm order_items
            $DB->prepare("INSERT INTO order_items (order_id, prod_id, qty, price) VALUES (?, ?, ?, ?)")
               ->execute([$order_id, $item['prod_id'], $item['prod_qty'], $item['price']]);

            // Giảm stock
            $DB->prepare("UPDATE product SET quantity = quantity - ? WHERE id = ?")
               ->execute([$item['prod_qty'], $item['prod_id']]);
        }

        // Xóa cart
        $DB->prepare("DELETE FROM carts WHERE user_id = ?")->execute([$user_id]);

        $success = "Đặt hàng thành công! Mã đơn: <strong>$tracking_no</strong>. Chúng tôi sẽ liên hệ sớm.";
        redirect("my-orders.php?success=1&order_id=$order_id");
    }
}
?>

<!-- Banner thanh toán -->
<div class="bg-light py-4 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-1">Thanh Toán Đơn Hàng</h2>
                <p class="text-muted mb-0">Tổng tạm tính: <?= formatMoney($subtotal) ?></p>
            </div>
            <div class="col-md-4 text-end">
                <a href="cart.php" class="btn btn-outline-secondary">Quay Lại Giỏ Hàng</a>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if (empty($error)): ?>
        <form method="POST" id="checkoutForm">
            <!-- Thông tin giao hàng -->
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-3">Thông Tin Giao Hàng</h4>
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user_data['name'] ?? $_POST['name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user_data['email'] ?? $_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($user_data['phone'] ?? $_POST['phone'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ giao hàng *</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="comments" class="form-label">Ghi chú đơn hàng</label>
                        <textarea class="form-control" id="comments" name="comments" rows="2"><?= htmlspecialchars($_POST['comments'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h4 class="mb-3">Vận Chuyển & Thanh Toán</h4>
                    
                    <!-- Chọn shipping -->
                    <div class="mb-4">
                        <label for="shipping" class="form-label">Đơn vị vận chuyển</label>
                        <select class="form-select" id="shipping" name="shipping" required onchange="updateShippingFee()">
                            <option value="">Chọn vận chuyển</option>
                            <?php foreach ($shipping_units as $ship): ?>
                                <option value="<?= $ship['id'] ?>" data-price="<?= $ship['price'] ?>">
                                    <?= htmlspecialchars($ship['name_ship']) ?> - <?= formatMoney($ship['price']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Phí ship hiển thị -->
                    <div class="mb-3">
                        <strong>Phí vận chuyển: <span id="shipping-fee-display">0 ₫</span></strong>
                    </div>
                    
                    <!-- Phương thức thanh toán -->
                    <div class="mb-4">
                        <label class="form-label">Phương thức thanh toán</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_mode" id="cod" value="COD" required checked>
                            <label class="form-check-label" for="cod">Thanh toán khi nhận hàng (COD)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_mode" id="transfer" value="Chuyển khoản">
                            <label class="form-check-label" for="transfer">Chuyển khoản ngân hàng</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_mode" id="card" value="Thẻ tín dụng">
                            <label class="form-check-label" for="card">Thẻ tín dụng/Debit</label>
                        </div>
                    </div>
                    
                    <!-- Tổng tiền -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span id="subtotal"><?= formatMoney($subtotal) ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span id="shipping-fee">0 ₫</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold fs-4 text-danger">
                                <span>Tổng cộng:</span>
                                <span id="grand-total"><?= formatMoney($subtotal) ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mt-3 btn-lg">Hoàn Tất Đặt Hàng</button>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

<script>
// Hàm format tiền JS (tương đương PHP formatMoney)
function formatMoneyJS(number) {
    return number.toLocaleString('vi-VN') + ' ₫';
}

// Cập nhật phí ship và tổng tiền khi chọn (sửa lỗi formatMoney)
function updateShippingFee() {
    const select = document.getElementById('shipping');
    const feeEl = document.getElementById('shipping-fee');
    const totalEl = document.getElementById('grand-total');
    const subtotalEl = document.getElementById('subtotal');
    const subtotal = parseFloat(subtotalEl.textContent.replace(/[^\d]/g, '')) || 0;  // Lấy subtotal từ text (bỏ ₫)
    const shippingFeeDisplay = document.getElementById('shipping-fee-display');
    
    if (select.value) {
        const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
        let finalPrice = price;
        
        // Miễn phí ship nếu subtotal >= 300k
        if (subtotal >= 300000) {
            finalPrice = 0;
            shippingFeeDisplay.textContent = 'Miễn phí (đơn > 300k)';
        } else {
            shippingFeeDisplay.textContent = formatMoneyJS(price);
        }
        
        feeEl.textContent = formatMoneyJS(finalPrice);
        totalEl.textContent = formatMoneyJS(subtotal + finalPrice);
    } else {
        shippingFeeDisplay.textContent = 'Chọn đơn vị vận chuyển';
        feeEl.textContent = '0 ₫';
        totalEl.textContent = formatMoneyJS(subtotal);
    }
}

// Đảm bảo script chạy sau khi DOM load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checkout loaded - JS ready');  // Debug: Xóa sau test
    updateShippingFee();  // Gọi ban đầu nếu có default
});
</script>