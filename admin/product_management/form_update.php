 <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sửa</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php 
	if(empty($_GET['id'])){
		header('location:index.php?error=truyen ma de chinh sua');
	}
	$id = $_GET['id'];
	include '../menu.php';
	require '../connect.php';
	$sql = "select * from product
	where id = '$id'";
	$res = mysqli_query($connect, $sql);
	$tmp = mysqli_fetch_array($res);
 ?>
<div class="container">
	<a href="index.php" class="menu-link">Quay về menu</a>
	<form action="process_update.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?=$tmp['id']?>">
		<label for="name">Tên</label>
		<input type="text" name="name" value="<?=$tmp['productName']?>">

		<label for="name">Mã loại hàng</label>
		<input type="text" id="catID" name="catID" value="<?=$tmp['catid']?>">

		<label for="name">Mô tả</label>
		<input type="text" id="desc" name="desc" value="<?=$tmp['product_desc']?>" >

		<label for="name">Số lượng</label>
		<input type="text" id="quant" name="quant" value="<?=$tmp['quantity']?>" >

		<label for="name">Giá</label>
		<input type="text" id="price" name="price" value="<?=$tmp['price']?>" >

		Giữ hình cũ: 
		<img src="../../assets/uploads/<?= htmlspecialchars($tmp['image']) ?>" width="100">
		<input type="hidden" name="img_old" value="<?=$tmp['image'] ?>">
		Hoặc thay mới 
		<input type="file" name="img" value="<?=$tmp['image']?>">
		<br>
		<button>Lưu</button>
	</form>
</div>
</body>
</html>