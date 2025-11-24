<?php 

if(empty($_GET['id'])){
	header('location:index.php?error=truyen ma de chinh sua');
	exit;
}

$id = $_GET['id'];

$name = $_GET['name'];
$img = $_GET['img'];

require '../connect.php';
$sql = "delete from category
where
id = '$id'
";

mysqli_query($connect, $sql);

$error = mysqli_error($connect);
mysqli_close($connect);

header('location:index.php? success=xoa thanh cong');



