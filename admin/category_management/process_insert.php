<?php 

if(empty($_POST['name']) || empty($_FILES['img']['name'])){
	header('location:form_insert.php? error=*Điền đầy đủ thông tin!');
	exit();
}

$name = $_POST['name'];
$img = $_FILES['img'];

$folder = '../../assets/uploads/';
$file_extension = explode('.', $img['name'])[1];
$file_name = time() . '.' . $file_extension;
$path_file = $folder . $file_name;

move_uploaded_file($img['tmp_name'], $path_file);

require '../connect.php';
$sql = "insert into category(name,image) 
values('$name','$file_name')";

mysqli_query($connect, $sql);
mysqli_close($connect);

header('location:index.php? success=*Thêm loại hàng thành công!');

