// jQuery cho AJAX (nếu chưa có, thêm <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> vào <head> các trang)
$(document).ready(function() {
    // Xử lý click nút "Thêm giỏ"
    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        
        var prodId = $(this).data('id');  // Lấy ID sản phẩm từ data-id
        var btn = $(this);  // Nút hiện tại
        var originalText = btn.text();  // Lưu text gốc
        
        // Kiểm tra đăng nhập (nếu không, redirect login)
        if (!isLoggedIn()) {  // Giả sử có hàm JS check session (hoặc dùng PHP echo)
            alert('Vui lòng đăng nhập để thêm giỏ hàng!');
            window.location.href = 'login.php';
            return;
        }
        
        // Disable nút tạm thời + loading
        btn.prop('disabled', true).text('Đang thêm...');
        
        // AJAX gọi API cart.php
        $.ajax({
            url: 'api/cart.php',
            type: 'POST',
            data: {
                action: 'add',
                prod_id: prodId,
                qty: 1  // Số lượng mặc định = 1
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Thêm sản phẩm vào giỏ hàng thành công!');
                    
                    // Cập nhật badge giỏ hàng ở navbar (nếu có)
                    updateCartBadge();
                    
                    // Optional: Cập nhật nút thành "Đã thêm"
                    btn.text('Đã thêm!').removeClass('btn-outline-danger').addClass('btn-success');
                    setTimeout(function() {
                        btn.text(originalText).prop('disabled', false).removeClass('btn-success').addClass('btn-outline-danger');
                    }, 2000);
                } else {
                    alert('Lỗi: ' + (response.msg || 'Không thể thêm sản phẩm!'));
                    btn.prop('disabled', false).text(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                alert('Lỗi kết nối! Vui lòng thử lại.');
                btn.prop('disabled', false).text(originalText);
            }
        });
    });
    
    // Hàm cập nhật badge số lượng giỏ hàng (gọi sau khi thêm)
    function updateCartBadge() {
        $.get('api/cart.php?action=get', function(response) {
            if (response.total_qty > 0) {
                $('.cart-badge').text(response.total_qty).show();  // Giả sử có class .cart-badge ở navbar
            } else {
                $('.cart-badge').hide();
            }
        }, 'json');
    }
    
    // Tự động cập nhật badge khi load trang (nếu login)
    if (isLoggedIn()) {
        updateCartBadge();
    }
});

// Hàm JS check login (dựa trên PHP echo, thêm vào <head> nếu cần)
function isLoggedIn() {
    return <?= json_encode(isLoggedIn()) ?>;
}