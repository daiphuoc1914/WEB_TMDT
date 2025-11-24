<?php include '../menu.php'; ?>

<?php 
include '../connect.php';

// Lấy từ khóa tìm kiếm
$key = '';
if (isset($_GET['key'])) {
    $key = $_GET['key'];
}

// Lấy danh sách sản phẩm
$sql = "SELECT * FROM product WHERE productName LIKE '%$key%'";
$result = mysqli_query($connect, $sql);

if (!$result) {
    echo "<div class='alert alert-danger'>Lỗi truy vấn: " . mysqli_error($connect) . "</div>";
}
?>

<h2 class="mb-4 text-gray-800">Product List</h2>
<form class="mb-3" method="GET">
    <input type="text" name="key" class="form-control w-25 d-inline"
           placeholder="Search..." value="<?= htmlspecialchars($key) ?>">
    <button class="btn btn-primary">Search</button>
</form>
<a href="form_insert.php" class="btn btn-success mb-3">Add Product</a>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['productName']) ?></td>
                            <td><?= htmlspecialchars($row['product_desc']) ?></td>
                            <td>
                                <img src="../../assets/uploads/<?= htmlspecialchars($row['image']) ?>"
                                     alt="Product Image" width="115">
                            </td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= number_format($row['price']) ?></td>
                            <td class="action-links">
                                <a href="form_update.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-primary">Edit</a>

                                <a href="delete.php?id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this product?');">
                                   Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Không có sản phẩm nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
