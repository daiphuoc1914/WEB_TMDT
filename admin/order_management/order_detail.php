<?php include '../menu.php'; ?>

<?php 
include '../connect.php';

$order_id = $_GET['id'];

$sql = "
    SELECT 
        order_items.qty,
        order_items.price AS item_price,
        product.productName,
        product.image,
        shipping_unit.price AS shipping
    FROM order_items
    JOIN product ON product.id = order_items.prod_id
    JOIN orders ON orders.id = order_items.order_id
    JOIN shipping_unit ON shipping_unit.id = orders.shipping
    WHERE order_items.order_id = '$order_id'
";

$result = mysqli_query($connect, $sql);

$sum = 0;
$shipping = 0;

if (!$result) {
    echo "<div class='alert alert-danger'>Lỗi truy vấn: " . mysqli_error($connect) . "</div>";
}
?>

<h2 class="mb-4 text-gray-800">Product List</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Ảnh</th>
                    <th>Tên</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>
                                <img src="../../assets/uploads/<?= htmlspecialchars($row['image']) ?>"
                                     alt="Product Image" width="115">
                            </td>
                            <td><?= htmlspecialchars($row['productName']) ?></td>
                            <td><?= htmlspecialchars($row['qty']) ?></td>
                            <td>
                                <?php  
                                    $res = $row['item_price'] * $row['qty'];
                                    echo $res;
                                    $sum += $res;
                                    $shipping = $row['shipping']
                                 ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Không có sản phẩm nào</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <h3>Tổng tiền hàng: <?= number_format($sum, 0, ',', '.') ?> VNĐ</h3>
    <h3>Tiền vận chuyển: <?= number_format($shipping, 0, ',', '.') ?> VNĐ</h3>
    <h2 class="text-danger">Tổng hóa đơn: <?= number_format($sum + $shipping, 0, ',', '.') ?> VNĐ</h2>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
