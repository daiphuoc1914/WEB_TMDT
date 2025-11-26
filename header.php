<?php
// Require config và functions (chỉ 1 lần ở đây)
require_once 'inc/config.php';
require_once 'inc/functions.php';

$page_title = isset($page_title) ? $page_title . ' - ' : '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $page_title ?>VPP Store - Văn Phòng Phẩm Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- jQuery CDN (thêm ở đây để load trước main.js) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Nội dung trang sẽ được include sau header này -->