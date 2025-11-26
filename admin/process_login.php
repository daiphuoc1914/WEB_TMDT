<?php
session_start();
include 'connect.php';

if (empty($_POST['email']) || empty($_POST['password'])) {
    header("Location: login.php?error=Chưa nhập email hoặc mật khẩu");
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];
$remember = isset($_POST['remember']) ? true : false;

$sql = "SELECT id, name FROM users WHERE email = '$email' AND password ='$password'";
$res = mysqli_query($connect, $sql);

if (mysqli_num_rows($res) == 1) {
    $each = mysqli_fetch_array($res);
    
    // Lưu Session
    $_SESSION['id'] = $each['id'];
    $_SESSION['name'] = $each['name'];

    if($remember){
        $token = uniqid('user_', true);
        $id = $each['id'];
        $sql_update = "UPDATE users SET token = '$token' WHERE id = '$id'";
        mysqli_query($connect, $sql_update);
        setcookie('remember', $token, time() + 60*60*24*30, '/');
    }
    
    // --- KHẮC PHỤC LỖI: Lưu session ngay lập tức trước khi chuyển trang ---
    session_write_close(); 
    // ---------------------------------------------------------------------

    // Chuyển hướng vào trang quản lý sản phẩm
    header("Location: product_management/index.php");
    exit();
} else {
    session_start(); // Đảm bảo session tồn tại để lưu lỗi
    $_SESSION['error'] = 'Sai email hoặc mật khẩu';
    header("Location: login.php");
    exit();
}

mysqli_close($connect);
?>