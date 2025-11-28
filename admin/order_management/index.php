<?php include '../menu.php'; ?>

<?php 
include '../connect.php';

$sql = "select 
orders.*,
users.name,
users.email,
users.phone
from orders
join users
on users.id = orders.user_id";

$result = mysqli_query($connect, $sql);

if (!$result) {
    echo "<div class='alert alert-danger'>Lỗi truy vấn: " . mysqli_error($connect) . "</div>";
}
?>

<h2 class="mb-4 text-gray-800">Danh sách đơn hàng</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Mã</th>
                    <th>Thời gian đặt</th>
                    <th>Mã đơn</th>
                    <th>Tên người đặt</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Trạng thái</th>
                    <th>Quản lý</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
						<tr onclick="window.location='order_detail.php?id=<?= $row['id'] ?>'" 
						    style="cursor:pointer;">
						    <td><?= htmlspecialchars($row['id']) ?></td>
						    <td><?= htmlspecialchars($row['created_at']) ?></td>
						    <td><?= htmlspecialchars($row['tracking_no']) ?></td>
						    <td><?= htmlspecialchars($row['name']) ?></td>
						    <td><?= htmlspecialchars($row['email']) ?></td>
						    <td><?= htmlspecialchars($row['phone']) ?></td>
						    <td><?= htmlspecialchars($row['address']) ?></td>
						    <td>
						        <?php 
						            switch ($row['status']){
						                case '0':
						                    echo "Mới đặt";
						                    break;
						                case '1':
						                    echo "Đã duyệt";
						                    break;
						                case '5':
						                    echo "Đã hủy";
						                    break; 
						            }
						        ?>
						    </td>
						    <?php 
						    	if($row['status'] == 0){ ?>
									<td class="action-links">
								        <a href="process_update.php?id=<?= $row['id'] ?>&status=1" 
								           class="btn btn-sm btn-primary"
								           onclick="event.stopPropagation();">
								           Duyệt đơn
								        </a>

								        <a href="process_update.php?id=<?= $row['id'] ?>&status=5"  
								           class="btn btn-sm btn-danger"
								           onclick="event.stopPropagation();">
								           Hủy đơn
								        </a>
								    </td>
						     <?php } ?>
						   
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
