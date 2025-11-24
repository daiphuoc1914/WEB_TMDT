<?php 

if(empty($_POST['name']) || empty($_POST['desc']) || empty($_POST['price']) || empty($_POST['quant']) || empty($_FILES['img']['name'])){
	header('location:form_insert.php? error=*Điền đầy đủ thông tin!');
	exit();
}

$name = $_POST['name'];
$catID = $_POST['catID'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$quant = $_POST['quant'];
$img = $_FILES['img'];

$folder = '../../assets/uploads/';
$file_extension = explode('.', $img['name'])[1];
$file_name = time() . '.' . $file_extension;
$path_file = $folder . $file_name;

move_uploaded_file($img['tmp_name'], $path_file);

require '../connect.php';
$sql = "insert into product(productName,catid ,product_desc, image, quantity, price) 
values('$name', '$catID','$desc','$file_name', '$quant', '$price')";

mysqli_query($connect, $sql);
mysqli_close($connect);

header('location:index.php? success=*Thêm sản phẩm thành công!');