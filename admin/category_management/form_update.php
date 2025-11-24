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
	$sql = "select * from category
	where id = '$id'";
	$res = mysqli_query($connect, $sql);
	$tmp = mysqli_fetch_array($res);
 ?>
<div class="container">
	<a href="index.php" class="menu-link">Quay về menu</a>
	<form action="process_update.php" method="post" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?=$tmp['id']?>">
		Ten
		<input type="text" name="name" value="<?=$tmp['name']?>">
		Keep your picture: 
		<img src="../../assets/uploads/<?= htmlspecialchars($tmp['image']) ?>" width="100">
		<input type="hidden" name="img_old" value="<?=$tmp['image'] ?>">
		Or change 
		<input type="file" name="img" value="<?=$tmp['image']?>">
		<br>
		<button>Lưu</button>
	</form>
</div>
</body>
</html>