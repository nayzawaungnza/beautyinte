<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$sql = "SELECT ps.id, p.name as product_name, c.name as customer_name, ps.qty, ps.total_price, ps.sale_date FROM product_sales ps INNER JOIN products p ON ps.product_id = p.id INNER JOIN customers c ON ps.customer_id = c.id ORDER BY ps.id DESC";
$sales = $mysqli->query($sql);
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>Product Sale List</h3>
            <div class="">
                <a href="product_sale_create.php" class="btn btn-primary">Create Product Sale</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== '') { ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php } ?>
                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Customer</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Sale Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($sales && $sales->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $sales->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['qty']) ?></td>
                                            <td><?= htmlspecialchars($row['total_price']) ?></td>
                                            <td><?= htmlspecialchars($row['sale_date']) ?></td>
                                            <td>
                                                <a href="product_sale_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2">Edit</a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No product sales found.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>