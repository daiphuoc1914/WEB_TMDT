<?php 

if(empty($_GET['id'])){
	header('location:index.php?error=truyen ma de chinh sua');
	exit;
}

$id = $_GET['id'];

$name = $_POST['name'];
$catID = $_POST['catID'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$quant = $_POST['quant'];
$img = $_FILES['img'];

require '../connect.php';
$sql = "delete from product
where
id = '$id'
";

mysqli_query($connect, $sql);

$error = mysqli_error($connect);
mysqli_close($connect);

header('location:index.php? success=xoa thanh cong');