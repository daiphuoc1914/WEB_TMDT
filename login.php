<?php
require 'inc/config.php';
require 'inc/functions.php';

$error = '';

if($_POST) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if(empty($email) || empty($password)) {
        $error = "Vui lòng nhập đầy đủ email và mật khẩu!";
    } else {
        $stmt = $DB->prepare("SELECT * FROM users WHERE email=? AND status=1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && $password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_type'] = $user['type'];
            $_SESSION['user_email'] = $user['email'];
            redirect('index.php');
        } else {
            $error = "Email hoặc mật khẩu không đúng! Vui lòng thử lại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - VPP Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-card { box-shadow: 0 0 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card">
                    <div class="card-header"><h4>Đăng Nhập Tài Khoản</h4></div>
                    
                    <div class="card-body p-4">
                        <?php if($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="Nhập email của bạn" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" required>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập</label>
                                </div>
                                <a href="#" class="small text-primary">Quên mật khẩu?</a>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mb-3">Đăng Nhập</button>
                        </form>
                        
                        <div class="text-center">
                            <p class="mb-0">Chưa có tài khoản? <a href="register.php" class="text-primary">Đăng ký ngay</a></p>
                        </div>
                        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>