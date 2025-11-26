<?php 
session_start();
require 'connect.php';

if(isset($_COOKIE['remember']) && !isset($_SESSION['id'])){
    $token = $_COOKIE['remember'];
    $sql = "SELECT * FROM users WHERE token = '$token'";
    $res = mysqli_query($connect, $sql);
    if(mysqli_num_rows($res) == 1){
        $each = mysqli_fetch_array($res);
        $_SESSION['id'] = $each['id'];
        $_SESSION['name'] = $each['name'];
    }
}

// 2. Logic Quan Trọng: Chỉ chuyển hướng nếu ĐÃ ĐĂNG NHẬP
if(isset($_SESSION['id'])){
    // Đã đăng nhập rồi thì đá sang trang quản lý sản phẩm
    header('location:product_management/index.php');
    exit();
}

// Nếu chưa đăng nhập thì Code chạy tiếp xuống dưới để hiện Form
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Đăng nhập Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
      body {
          background-color: #f8f9fc;
          height: 100vh;
          display: flex;
          align-items: center;
          justify-content: center;
      }
      .login-container {
          width: 400px;
          padding: 30px;
          background: white;
          border-radius: 10px;
          box-shadow: 0 0 10px rgba(0,0,0,0.1);
      }
  </style>
</head>
<body>
<div class="login-container">
    <h3 class="text-center mb-4">Đăng nhập</h3>
    
    <form action="process_login.php" method="post">
        <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger">
              <?php 
                  echo $_SESSION['error']; 
                  unset($_SESSION['error']);  
              ?>
          </div>
        <?php endif; ?>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="text" name="email" class="form-control" placeholder="admin@gmail.com" required>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>

      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
      </div>
      
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>
</body>
</html>