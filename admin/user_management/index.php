<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management</title>
    </head>
<body>
    
    <?php include '../menu.php'; ?>

    <?php 
    include '../connect.php';
    $sql = "select * from users
    where type = 0";
    $res = mysqli_query($connect, $sql);
    ?>

    <h2 class="mb-4 text-gray-800">User List</h2>
    <a href="form_insert.php" class="btn btn-success mb-3">Add User</a>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res && mysqli_num_rows($res) > 0): foreach ($res as $idx): ?>
                        <tr>
                            <td><?= htmlspecialchars($idx['id']) ?></td>
                            <td><?= htmlspecialchars($idx['name']) ?></td>
                            <td><?= htmlspecialchars($idx['email']) ?></td>
                            <td><?= htmlspecialchars($idx['phone']) ?></td>
                            <td class="action-links">
                                <a href="form_update.php?id=<?= $idx['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="delete.php?id=<?= $idx['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="5" class="text-center">No Data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </div> </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>