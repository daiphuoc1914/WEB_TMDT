<?php 

if(empty($_POST['id'])){
	header('location:index.php?error=*Truyền mã để sửa');
	exit;
}

$id = $_POST['id'];

$name = $_POST['name'];
$catID = $_POST['catID'];
$desc = $_POST['desc'];
$price = $_POST['price'];
$quant = $_POST['quant'];
$img_new = $_FILES['img'];


if($img_new['size']>0){
	$folder = '../../assets/uploads/';
	$file_extension = explode('.', $img_new['name'])[1];
	$file_name = time() . '.' . $file_extension;
	$path_file = $folder . $file_name;

	move_uploaded_file($img_new['tmp_name'], $path_file);
}
else{
	$file_name = $_POST['img_old'];
}



require '../connect.php';
$sql = "update product
set
productName = '$name',
catid = '$catID',
product_desc = '$desc',
image ='$file_name',
quantity = '$quant',
price = '$price'
where
id = '$id'
";

mysqli_query($connect, $sql);

$error = mysqli_error($connect);
mysqli_close($connect);
if(empty($error)){
	header('location:index.php?success=*Sửa thành công!');
}else{
	header("location:form_update.php?id=$id&error=*Lỗi truy vấn!!");
}


