<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row gy-4">
            <!-- Cột 1: Thông tin cửa hàng -->
            <div class="col-lg-4 col-md-6">
                <h4 class="text-danger fw-bold mb-3">StudyHub</h4>
                <p class="small text-light">
                    Chuyên cung cấp văn phòng phẩm chất lượng cao, giá tốt nhất thị trường.<br>
                    Hỗ trợ giao hàng nhanh toàn quốc
                </p>
                <div class="mt-3">
                    <a href="tel:+84900000000" class="text-white text-decoration-none">
                        <i class="fas fa-phone-alt me-2"></i> +84 900 000 000
                    </a><br>
                    <a href="mailto:vpp@hotro.com" class="text-white text-decoration-none">
                        <i class="fas fa-envelope me-2"></i> stdh@hotro.com
                    </a>
                </div>
            </div>

            <!-- Cột 2: Liên kết nhanh -->
            <div class="col-lg-2 col-md-6">
                <h5 class="text-danger fw-bold mb-3">Liên Kết</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-light text-decoration-none hover-text-danger">Trang chủ</a></li>
                    <li class="mb-2"><a href="category.php?all=1" class="text-light text-decoration-none hover-text-danger">Danh mục</a></li>
                    <li class="mb-2"><a href="cart.php" class="text-light text-decoration-none hover-text-danger">Giỏ hàng</a></li>
                    <li class="mb-2"><a href="wishlist.php" class="text-light text-decoration-none hover-text-danger">Yêu thích</a></li>
                    <li class="mb-2"><a href="my-orders.php" class="text-light text-decoration-none hover-text-danger">Đơn hàng</a></li>
                </ul>
            </div>

            <!-- Cột 3: Liên hệ hỗ trợ (form) -->
            <div class="col-lg-3 col-md-6">
                <h5 class="text-danger fw-bold mb-3">Liên Hệ Hỗ Trợ</h5>
                <form action="send_contact.php" method="POST"> <!-- Bạn tạo file xử lý sau nếu cần -->
                    <div class="mb-2">
                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Họ tên" required>
                    </div>
                    <div class="mb-2">
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="Email" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="Số điện thoại">
                    </div>
                    <div class="mb-2">
                        <textarea name="message" rows="2" class="form-control form-control-sm" placeholder="Nội dung..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">Gửi</button>
                </form>
            </div>

            <!-- Cột 4: Bản đồ Google -->
            <div class="col-lg-3">
                <h5 class="text-danger fw-bold mb-3">Địa Chỉ</h5>
                <p class="small text-light mb-2">
                    Số 2 - Võ Oanh - Phường 25 - Bình Thạnh - TP.Hồ Chí Minh
                </p>
                <div class="ratio ratio-16x9">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.0887067578224!2d106.71414257485729!3d10.804517789345965!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3175293dceb22197%3A0x755bb0f39a48d4a6!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBHaWFvIFRow7RuZyBW4bqtbiBU4bqjaSBUaMOgbmggUGjhu5EgSOG7kyBDaMOtIE1pbmggLSBDxqEgc-G7nyAx!5e0!3m2!1svi!2s!4v1751948582445!5m2!1svi!2s" 
                        allowfullscreen="" loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        style="border:0; border-radius: 8px;">
                    </iframe>
                </div>
            </div>
        </div>

        <!-- Dòng cuối cùng -->
        <hr class="bg-secondary my-4">
        <div class="text-center small">
            © 2025 <span class="text-danger fw-bold">StudyHub</span>. Đã đăng ký bản quyền. 
            Phát triển bởi <span class="text-danger">Nhóm 3</span>
        </div>
    </div>
</footer>

<!-- Font Awesome (nếu chưa có) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- CSS tùy chỉnh cho footer (thêm vào assets/css/style.css hoặc trong thẻ <style>) -->
<style>
footer a:hover { color: #dc3545 !important; transition: 0.3s; }
footer .form-control { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: white; }
footer .form-control::placeholder { color: rgba(255,255,255,0.7); }
footer .form-control:focus { background: white; color: black; box-shadow: 0 0 0 0.2rem rgba(220,53,69,0.25); }
.hover-text-danger:hover { color: #dc3545 !important; }
</style>

<!-- Script Bootstrap & main.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>