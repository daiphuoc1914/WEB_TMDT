<?php
// Ngăn việc include nhiều lần gây lỗi redeclare function
if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isLoggedIn() && $_SESSION['user_type'] == 1;
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: $url");
        exit();
    }
}

if (!function_exists('uploadImage')) {
    function uploadImage($file, $target_dir = "assets/uploads/") {
        if ($file['error'] !== UPLOAD_ERR_OK) return false;
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . "_" . rand(1000,9999) . "." . strtolower($ext);
        $target = $target_dir . $filename;
        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $filename;
        }
        return false;
    }
}

if (!function_exists('formatMoney')) {
    function formatMoney($number) {
        return number_format($number, 0, ',', '.') . ' ₫';
    }
}
?>