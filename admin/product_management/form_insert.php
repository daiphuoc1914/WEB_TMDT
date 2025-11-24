<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Thêm Sản Phẩm</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>


<div class="container">
	<h1>Thêm Sản Phẩm</h1>
	<a href="index.php" class="menu-link">Quay về menu</a>
	<form action="process_insert.php" method="post" enctype="multipart/form-data">
		<label for="name">Tên</label>
		<input type="text" id="name" name="name" placeholder="Nhập sản phẩm" >
		<label for="name">Mã loại hàng</label>
		<input type="text" id="catID" name="catID" placeholder="Nhập mã loại hàng" >
		<label for="name">Mô tả</label>
		<input type="text" id="desc" name="desc" placeholder="Nhập mô tả" >
		<label for="name">Số lượng</label>
		<input type="text" id="quant" name="quant" placeholder="Nhập số lượng" >
		<label for="name">Giá</label>
		<input type="text" id="price" name="price" placeholder="Nhập giá" >
		<label for="img">Ảnh</label>
		<input type="file" id="img" name="img" placeholder="Nhập đường dẫn hình ảnh" >
		
		<button type="submit">Thêm</button>
	</form>
</div>

</body>
</html>
