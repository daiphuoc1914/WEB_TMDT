<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Thêm Loại Hàng</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>


<div class="container">
	<h1>Thêm Loại Hàng</h1>
	<a href="index.php" class="menu-link">Quay về menu</a>
	<form action="process_insert.php" method="post" enctype="multipart/form-data">
		<label for="name">Tên</label>
		<input type="text" id="name" name="name" placeholder="Nhập tên loại hàng" >
		
		<label for="img">Ảnh</label>
		<input type="file" id="img" name="img" placeholder="Nhập đường dẫn hình ảnh" >
		
		<button type="submit">Thêm</button>
	</form>
</div>

</body>
</html>
