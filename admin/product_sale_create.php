<?php
// require '../require/check_auth.php';
require '../require/db.php';
require '../require/common.php';

$error = false;
$product_id_error = $customer_id_error = $qty_error = $sale_date_error = '';
$product_id = $customer_id = $qty = $sale_date = '';
$total_price = 0;

// Fetch products and customers
$products = $mysqli->query("SELECT id, name, price FROM products ORDER BY name ASC");
$customers = $mysqli->query("SELECT id, name FROM customers ORDER BY name ASC");

if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $product_id = $mysqli->real_escape_string($_POST['product_id']);
    $customer_id = $mysqli->real_escape_string($_POST['customer_id']);
    $qty = $mysqli->real_escape_string($_POST['qty']);
    $sale_date = $mysqli->real_escape_string($_POST['sale_date']);
    // Get product price
    $product_row = $mysqli->query("SELECT price FROM products WHERE id='$product_id'")->fetch_assoc();
    $price = $product_row ? $product_row['price'] : 0;
    $total_price = $qty * $price;

    if (empty($product_id) || !is_numeric($product_id)) {
        $error = true;
        $product_id_error = "Please select a product.";
    }
    if (empty($customer_id) || !is_numeric($customer_id)) {
        $error = true;
        $customer_id_error = "Please select a customer.";
    }
    if (empty($qty) || !is_numeric($qty) || $qty <= 0) {
        $error = true;
        $qty_error = "Please enter a valid quantity.";
    }
    if (empty($sale_date)) {
        $error = true;
        $sale_date_error = "Please select a sale date.";
    }

    if (!$error) {
        $sql = "INSERT INTO product_sales (product_id, customer_id, qty, total_price, sale_date) VALUES ('$product_id', '$customer_id', '$qty', '$total_price', '$sale_date')";
        $result = $mysqli->query($sql);
        if ($result) {
            echo "<script>window.location.href = 'product_sale_list.php?success=Product Sale Created';</script>";
            exit;
        } else {
            $error = true;
            $sale_date_error = "Product Sale Create Failed.";
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>Product Sale Create</h3>
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
                                <label for="product_id" class="form-label">Product</label>
                                <select name="product_id" class="form-control" id="product_id">
                                    <option value="">Select Product</option>
                                    <?php if ($products && $products->num_rows > 0) {
                                        while ($row = $products->fetch_assoc()) {
                                            $selected = ($product_id == $row['id']) ? 'selected' : '';
                                            echo "<option value='{$row['id']}' data-price='{$row['price']}' $selected>{$row['name']}</option>";
                                        }
                                    } else {
                                        echo '<option value="">No products available</option>';
                                    } ?>
                                </select>
                                <?php if ($error && $product_id_error) { ?>
                                    <span class="text-danger"><?= $product_id_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-2">
                                <label for="customer_id" class="form-label">Customer</label>
                                <select name="customer_id" class="form-control" id="customer_id">
                                    <option value="">Select Customer</option>
                                    <?php if ($customers && $customers->num_rows > 0) {
                                        while ($row = $customers->fetch_assoc()) {
                                            $selected = ($customer_id == $row['id']) ? 'selected' : '';
                                            echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                        }
                                    } else {
                                        echo '<option value="">No customers available</option>';
                                    } ?>
                                </select>
                                <?php if ($error && $customer_id_error) { ?>
                                    <span class="text-danger"><?= $customer_id_error ?></span>
                                <?php } ?>
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
                            <button type="submit" class="btn btn-primary w-100">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var productSelect = document.getElementById('product_id');
        var qtyInput = document.getElementById('qty');
        var totalInput = document.getElementById('total_price');

        function updateTotal() {
            var selected = productSelect.options[productSelect.selectedIndex];
            var price = selected.getAttribute('data-price');
            var qty = qtyInput.value;
            if (price && qty) {
                totalInput.value = price * qty;
            } else {
                totalInput.value = '';
            }
        }
        if (productSelect && qtyInput && totalInput) {
            productSelect.addEventListener('change', updateTotal);
            qtyInput.addEventListener('input', updateTotal);
        }
    });
</script>
<?php require '../layouts/footer.php'; ?>