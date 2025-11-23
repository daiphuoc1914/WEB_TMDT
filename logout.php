<?php

require_once 'inc/functions.php';

// Khởi động session (nếu chưa)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra nếu đang đăng nhập (dựa trên user_id từ code trước)
if (isset($_SESSION['user_id'])) {
    // Unset tất cả session user-related (khớp với login/register)
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_type']);
    unset($_SESSION['user_email']);
    
    // Set message thành công
    $_SESSION['message'] = "Bạn đã đăng xuất tài khoản thành công!";
        
    // Redirect
    redirect('index.php');  // Hoặc header('Location: index.php'); exit();
} else {
    // Nếu chưa đăng nhập, redirect luôn
    $_SESSION['message'] = "Bạn chưa đăng nhập!";
    redirect('index.php');
}
?>