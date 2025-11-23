<?php
require 'inc/config.php';
require 'inc/functions.php';

if($_POST) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pass = trim($_POST['password']);

    try {
        $stmt = $DB->prepare("INSERT INTO users (name,email,phone,password) VALUES (?,?,?,?)");
        $stmt->execute([$name,$email,$phone,$pass]);
        echo "<script>alert('Đăng ký thành công!'); location.href='login.php';</script>";
    } catch(Exception $e) {
        $error = "Email đã tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html><head><title>Đăng ký</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><h4>Đăng Ký Tài Khoản</h4></div>
                <div class="card-body">
                    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="post">
                        <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Họ tên" required></div>
                        <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                        <div class="mb-3"><input type="text" name="phone" class="form-control" placeholder="Số điện thoại"></div>
                        <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Mật khẩu" required></div>
                        <button class="btn btn-primary w-100">Đăng ký</button>
                    </form>
                    <hr>
                    <a href="login.php" class="btn btn-link">Đã có tài khoản? Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body></html>