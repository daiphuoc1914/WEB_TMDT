<?php
// Chỉ khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db   = 'tmdt_vpp';
$user = 'root';
$pass = '';

try {
    $DB = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

date_default_timezone_set('Asia/Ho_Chi_Minh');
?>