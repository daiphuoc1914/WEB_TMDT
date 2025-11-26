<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { overflow: hidden; background-color: #f8f9fc; }
        .content-wrapper { display: flex; flex-direction: column; width: 100%; height: 100vh; overflow: hidden; }
        .topbar { height: 4.375rem; background-color: #fff; flex-shrink: 0; }
        .main-content { flex-grow: 1; padding: 20px; overflow-y: auto; }
    </style>
</head>
<body>

<div class="d-flex"> 
    <div class="d-flex flex-column p-3 bg-dark text-white" style="width: 250px; height: 100vh; flex-shrink: 0;">
        <h3 class="text-center mb-4">Dashboard</h3>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="../category_management/index.php" class="nav-link text-white"><i class="bi bi-folder"></i> Category</a>
            </li>
            <li class="nav-item mb-2">
                <a href="../product_management/index.php" class="nav-link text-white"><i class="bi bi-box-seam"></i> Product</a>
            </li>
            <li class="nav-item mb-2">
                <a href="../user_management/index.php" class="nav-link text-white"><i class="bi bi-people"></i> Users</a>
            </li>
        </ul>
    </div>

    <div class="content-wrapper">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow px-4">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <span class="me-2 d-none d-lg-inline text-secondary small fw-bold">
                            <?php echo $_SESSION['name']; ?>
                        </span>
                        <img class="img-profile rounded-circle" src="https://startbootstrap.github.io/startbootstrap-sb-admin-2/img/undraw_profile.svg" style="width:30px">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in">
                        <li><a class="dropdown-item" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div class="main-content">