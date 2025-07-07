<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo '<div class="alert alert-danger">Invalid sale ID.</div>';
    exit;
}
$sql = "SELECT ps.*, p.name as product_name, p.price as product_price, c.name as customer_name FROM product_sales ps INNER JOIN products p ON ps.product_id = p.id INNER JOIN customers c ON ps.customer_id = c.id WHERE ps.id = $id";
$result = $mysqli->query($sql);
if (!$result || $result->num_rows == 0) {
    echo '<div class="alert alert-danger">Product sale not found.</div>';
    exit;
}
$sale = $result->fetch_assoc();
$error = false;
$qty_error = $sale_date_error = '';
$qty = $sale['qty'];
$sale_date = $sale['sale_date'];
$total_price = $sale['total_price'];
if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $qty = $mysqli->real_escape_string($_POST['qty']);
    $sale_date = $mysqli->real_escape_string($_POST['sale_date']);
    $product_price = $sale['product_price'];
    $total_price = $qty * $product_price;
    if (empty($qty) || !is_numeric($qty) || $qty <= 0) {
        $error = true;
        $qty_error = "Please enter a valid quantity.";
    }
    if (empty($sale_date)) {
        $error = true;
        $sale_date_error = "Please select a sale date.";
    }
    if (!$error) {
        $sql = "UPDATE product_sales SET qty='$qty', total_price='$total_price', sale_date='$sale_date' WHERE id=$id";
        $result = $mysqli->query($sql);
        if ($result) {
            echo "<script>window.location.href = 'product_sale_list.php?success=Product Sale Updated';</script>";
            exit;
        } else {
            $error = true;
            $sale_date_error = "Product Sale Update Failed.";
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>Edit Product Sale</h3>
            <div class="">
                <a href="product_sale_list.php" class="btn btn-dark">Back</a>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-6 col-sm-10 col-12">
                <?php if ($error && $sale_date_error) { ?>
                    <div class="alert alert-danger">
                        <?= $sale_date_error ?>
                    </div>
                <?php } ?>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group mb-2">
                                <label class="form-label">Product</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($sale['product_name']) ?>" readonly />
                            </div>
                            <div class="form-group mb-2">
                                <label class="form-label">Customer</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($sale['customer_name']) ?>" readonly />
                            </div>
                            <div class="form-group mb-2">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" name="qty" class="form-control" id="qty" value="<?= htmlspecialchars($qty) ?>" min="1" />
                                <?php if ($error && $qty_error) { ?>
                                    <span class="text-danger"><?= $qty_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-2">
                                <label for="total_price" class="form-label">Total Price</label>
                                <input type="number" name="total_price" class="form-control" id="total_price" value="<?= htmlspecialchars($total_price) ?>" readonly />
                            </div>
                            <div class="form-group mb-2">
                                <label for="sale_date" class="form-label">Sale Date</label>
                                <input type="date" name="sale_date" class="form-control" id="sale_date" value="<?= htmlspecialchars($sale_date) ?>" />
                                <?php if ($error && $sale_date_error) { ?>
                                    <span class="text-danger"><?= $sale_date_error ?></span>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="form_sub" value="1" />
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var qtyInput = document.getElementById('qty');
        var totalInput = document.getElementById('total_price');
        var price = <?= json_encode($sale['product_price']) ?>;

        function updateTotal() {
            var qty = qtyInput.value;
            if (price && qty) {
                totalInput.value = price * qty;
            } else {
                totalInput.value = '';
            }
        }
        if (qtyInput && totalInput) {
            qtyInput.addEventListener('input', updateTotal);
        }
    });
</script>
<?php require '../layouts/footer.php'; ?>