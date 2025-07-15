<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

$error = false;
$product_id_error = $qty_error = $sale_date_error = '';
$product_id = $qty = $sale_date = '';
$total_price = 0;
$promotion_id = '';
$promotion_percent = 0;
$promotions = [];
$payment_method_id_error = '';
$payment_method_id = '';

// Fetch all promotions (for JS filtering)
$promotion_query = $mysqli->query("SELECT id, package_name, percentage, start_date, end_date FROM promotions WHERE percentage > 0");
if ($promotion_query && $promotion_query->num_rows > 0) {
    while ($prow = $promotion_query->fetch_assoc()) {
        $promotions[] = $prow;
    }
}

// Fetch products only (no customers)
$products = $mysqli->query("SELECT p.id, p.name, p.price FROM products p INNER JOIN product_qty pq ON p.id = pq.product_id WHERE pq.qty > 0 ORDER BY p.name ASC");

// Fetch payment methods
$payment_methods = $mysqli->query("SELECT id, name FROM payment_method WHERE status = 1 ORDER BY name ASC");

if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $product_id = $mysqli->real_escape_string($_POST['product_id']);
    $qty = $mysqli->real_escape_string($_POST['qty']);
    $sale_date = $mysqli->real_escape_string($_POST['sale_date']);
    $promotion_id = isset($_POST['promotion_id']) ? $mysqli->real_escape_string($_POST['promotion_id']) : '';
    $payment_method_id = isset($_POST['payment_method_id']) ? $mysqli->real_escape_string($_POST['payment_method_id']) : '';
    // Get product price
    $product_row = $mysqli->query("SELECT price FROM products WHERE id='$product_id'")->fetch_assoc();
    $price = $product_row ? $product_row['price'] : 0;
    $promotion_percent = 0;
    if ($promotion_id) {
        $promo_row = $mysqli->query("SELECT percentage FROM promotions WHERE id='$promotion_id'")->fetch_assoc();
        if ($promo_row) $promotion_percent = $promo_row['percentage'];
    }
    if ($promotion_percent > 0) {
        $total_price = $qty * $price * (1 - ($promotion_percent / 100));
    } else {
        $total_price = $qty * $price;
    }

    if (empty($product_id) || !is_numeric($product_id)) {
        $error = true;
        $product_id_error = "ကျေးဇူးပြု၍ ထုတ်ကုန်တစ်ခုရွေးချယ်ပါ။";
    }
    if (empty($qty) || !is_numeric($qty) || $qty <= 0) {
        $error = true;
        $qty_error = "ကျေးဇူးပြု၍ မှန်ကန်သောအရေအတွက်ထည့်ပါ။";
    }
    if (empty($sale_date)) {
        $error = true;
        $sale_date_error = "ကျေးဇူးပြု၍ ရောင်းချမည့်ရက်စွဲကို ရွေးချယ်ပါ။";
    }
    if (empty($payment_method_id) || !is_numeric($payment_method_id)) {
        $error = true;
        $payment_method_id_error = "ကျေးဇူးပြု၍ ငွေပေးချေနည်းလမ်းကို ရွေးချယ်ပါ။";
    }

    if (!$error) {
        // Check available quantity
        $qty_row = $mysqli->query("SELECT qty FROM product_qty WHERE product_id='$product_id'")->fetch_assoc();
        $available_qty = $qty_row ? (int)$qty_row['qty'] : 0;
        if ($qty > $available_qty) {
            $error = true;
            $qty_error = "Not enough quantity available. Only $available_qty left.";
        } else {
            $sql = "INSERT INTO product_sales (product_id, qty, total_price, sale_date, promotion_id, payment_method_id) VALUES ('$product_id', '$qty', '$total_price', '$sale_date', " . ($promotion_id ? "'$promotion_id'" : 'NULL') . ", '$payment_method_id')";
            $result = $mysqli->query($sql);
            if ($result) {
                // Subtract sold quantity from product_qty
                $mysqli->query("UPDATE product_qty SET qty = qty - $qty WHERE product_id = '$product_id'");
                echo "<script>window.location.href = 'product_sale_list.php?success=Product Sale Created';</script>";
                exit;
            } else {
                $error = true;
                $sale_date_error = "ထုတ်ကုန်ရောင်းချမှု ဖန်တီးရန် မအောင်မြင်ပါ။";
            }
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid mt-3">
        
        <div class="d-flex justify-content-center">
            <div class="col-md-6 col-sm-10 col-12">
                <?php if ($error && $sale_date_error) { ?>
                    <div class="alert alert-danger">
                        <?= $sale_date_error ?>
                    </div>
                <?php } ?>
                <div class="card">
                    <div class="card-body">
                         <h3 class="text-center mb-5 text-info">ပစ္စည်းအရောင်း</h3>
                        <form action="" method="POST">
                            <div class="form-group mb-2">
                                <label for="product_id" class="form-label">ပစ္စည်းများ</label>
                                <select name="product_id" class="form-control" id="product_id">
                                    <option value="">ပစ္စည်းများ ‌ရွေးချယ်ရန်</option>
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
                                <label for="qty" class="form-label">အရေအတွက်</label>
                                <input type="number" name="qty" class="form-control" id="qty" value="<?= htmlspecialchars($qty) ?>" min="1" />
                                <?php if ($error && $qty_error) { ?>
                                    <span class="text-danger"><?= $qty_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-2">
                                <label for="total_price" class="form-label">စုစုပေါင်း စျေးနှုန်းများ</label>
                                <input type="number" name="total_price" class="form-control" id="total_price" value="<?= htmlspecialchars($total_price) ?>" readonly />
                            </div>
                            <div class="form-group mb-2">
                                <label for="payment_method_id" class="form-label">ငွေပေးချေမှုနည်းလမ်း</label>
                                <select name="payment_method_id" class="form-control" id="payment_method_id" required>
                                    <option value="">ငွေပေးချေမှုနည်းလမ်း ရွေးချယ်ရန်</option>
                                    <?php if ($payment_methods && $payment_methods->num_rows > 0) {
                                        while ($row = $payment_methods->fetch_assoc()) {
                                            $selected = ($payment_method_id == $row['id']) ? 'selected' : '';
                                            echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                        }
                                    } else {
                                        echo '<option value="">No payment methods available</option>';
                                    } ?>
                                </select>
                                <?php if ($error && $payment_method_id_error) { ?>
                                    <span class="text-danger"><?= $payment_method_id_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-2" id="promotion-group" style="display:none;">
                                <label for="promotion_id" class="form-label">ပရိုမိုးရှင်း</label>
                                <select name="promotion_id" class="form-control" id="promotion_id">
                                    <option value="">ပရိုမိုးရှင်း ရွေးချယ်ရန်</option>
                                </select>
                            </div>

                            <div class="form-group mb-2">
                                <label for="sale_date" class="form-label">ရောင်းချသည့် ရက်စွဲ</label>
                                <input type="date" name="sale_date" class="form-control" id="sale_date" value="<?= htmlspecialchars($sale_date) ?>" />
                                <?php if ($error && $sale_date_error) { ?>
                                    <span class="text-danger"><?= $sale_date_error ?></span>
                                <?php } ?>
                            </div>

                            <input type="hidden" name="form_sub" value="1" />
                            <button type="submit" class="btn btn-primary w-100">ဖန်တီးရန်</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const allPromotions = <?= json_encode($promotions) ?>;
    document.addEventListener('DOMContentLoaded', function() {
        var productSelect = document.getElementById('product_id');
        var qtyInput = document.getElementById('qty');
        var totalInput = document.getElementById('total_price');
        var saleDateInput = document.getElementById('sale_date');
        var promotionGroup = document.getElementById('promotion-group');
        var promotionSelect = document.getElementById('promotion_id');

        function updatePromotionOptions() {
            // Clear options
            promotionSelect.innerHTML = '<option value="">ပရိုမိုးရှင်း ရွေးချယ်ရန်</option>';
            var saleDate = saleDateInput.value;
            var hasPromotion = false;
            if (saleDate) {
                allPromotions.forEach(function(promo) {
                    if (saleDate >= promo.start_date && saleDate <= promo.end_date) {
                        var opt = document.createElement('option');
                        opt.value = promo.id;
                        opt.text = promo.package_name + ' (' + promo.percentage + '%)';
                        opt.setAttribute('data-percent', promo.percentage);
                        promotionSelect.appendChild(opt);
                        hasPromotion = true;
                    }
                });
            }
            promotionGroup.style.display = hasPromotion ? '' : 'none';
        }

        function updateTotal() {
            var selected = productSelect.options[productSelect.selectedIndex];
            var price = selected.getAttribute('data-price');
            var qty = qtyInput.value;
            var percent = 0;
            var promoOpt = promotionSelect.options[promotionSelect.selectedIndex];
            if (promoOpt && promoOpt.value) {
                percent = promoOpt.getAttribute('data-percent');
            }
            if (price && qty) {
                var total = price * qty;
                if (percent > 0) {
                    total = total * (1 - (percent / 100));
                }
                totalInput.value = Math.round(total);
            } else {
                totalInput.value = '';
            }
        }
        if (productSelect && qtyInput && totalInput && saleDateInput && promotionSelect) {
            productSelect.addEventListener('change', updateTotal);
            qtyInput.addEventListener('input', updateTotal);
            saleDateInput.addEventListener('change', function() {
                updatePromotionOptions();
                updateTotal();
            });
            promotionSelect.addEventListener('change', updateTotal);
        }
        // Initial load
        updatePromotionOptions();
        updateTotal();
    });
</script>
<?php require '../layouts/footer.php'; ?>