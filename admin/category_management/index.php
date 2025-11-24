<?php include '../menu.php'; ?>

<?php 
include '../connect.php';

$sql = "select * from category";
$res = mysqli_query($connect, $sql);

if (!$res) {
    echo "<div class='alert alert-danger'>Lỗi truy vấn: " . mysqli_error($connect) . "</div>";
}
?>

<h2 class="mb-4 text-gray-800">Category List</h2>
<a href="form_insert.php" class="btn btn-success mb-3">Add Category</a>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($res) > 0): foreach ($res as $idx): ?>
                    <tr>
                        <td><?= htmlspecialchars($idx['id']) ?></td>
                        <td><?= htmlspecialchars($idx['name']) ?></td>
                        <td>
                            <img src="../../assets/uploads/<?= htmlspecialchars($idx['image']) ?>"
                                 alt="Category Image" width="115">
                        </td>
                        <td class="action-links">
                            <a href="form_update.php?id=<?= htmlspecialchars($idx['id']) ?>"
                               class="btn btn-sm btn-primary">Edit</a>

                            <a href="delete.php?id=<?= htmlspecialchars($idx['id']) ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Are you sure you want to delete this category?');">
                               Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center">Không có danh mục nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</div> </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>