<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name_err = $price_err = $desc_err = $quantity_err = '';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<script>window.location.href= 'product_list.php';</script>";
    exit;
}

// ✅ Fetch categories
$category_result = $mysqli->query("SELECT * FROM product_categories");

// ✅ Get product info
$sql = "SELECT products.id, product_qty.qty, products.name, products.description, products.price, products.category_id 
        FROM product_qty 
        INNER JOIN products ON products.id = product_qty.product_id 
        WHERE products.id = '$id'";
$oldData = $mysqli->query($sql)->fetch_assoc();

$name = $oldData['name'];
$price = $oldData['price'];
$desc = $oldData['description'];
$quantity = $oldData['qty'];
$category_id = $oldData['category_id'];

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $nameEdit = $_POST['name'];
    $priceEdit = $_POST['price'];
    $descEdit = $_POST['description'];
    $quantityEdit = $_POST['quantity'];
    $categoryEdit = $_POST['category_id'];

    // Validation
    if (empty($nameEdit)) {
        $error = true;
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } else if (strlen($nameEdit) < 5) {
        $error = true;
        $name_err = "အမည်သည် အနည်းဆုံး စာလုံး ၅ လုံး ပြည့်မီရပါမည်။";
    } else if (strlen($nameEdit) >= 100) {
        $error = true;
        $name_err = "အမည်သည် စာလုံး ၁၀၀ ထက်နည်းရပါမည်။";
    }

    if (empty($priceEdit)) {
        $error = true;
        $price_err = "ကျေးဇူးပြု၍ ဈေးနှုန်းထည့်ပါ။";
    } else if (!is_numeric($priceEdit)) {
        $error = true;
        $price_err = "ဈေးနှုန်းသည် ဂဏန်းဖြစ်ရပါမည်။";
    } else if ($priceEdit > 1000000) {
        $error = true;
        $price_err = "ဈေးနှုန်းသည် ၁,၀၀၀,၀၀၀ ကျပ်အောက်ဖြစ်ရပါမည်။";
    }

    if (empty($descEdit)) {
        $error = true;
        $desc_err = "ကျေးဇူးပြု၍ ဖော်ပြချက်ထည့်ပါ။";
    } else if (strlen($descEdit) > 1000) {
        $error = true;
        $desc_err = "ဖော်ပြချက်သည် စာလုံး ၁၀၀၀ ထက်နည်းရပါမည်။";
    }

    if (empty($quantityEdit)) {
        $error = true;
        $quantity_err = "ကျေးဇူးပြု၍ အရေအတွက်ထည့်ပါ။";
    } else if (!is_numeric($quantityEdit)) {
        $error = true;
        $quantity_err  = "အရေအတွက်တွင် ဂဏန်းများသာ ပါဝင်ရပါမည်။";
    }

    if (!$error) {
        // ✅ Update query with category_id
        $edit_sql = "UPDATE `product_qty` 
                        INNER JOIN `products` ON `products`.`id` = `product_qty`.`product_id` 
                     SET 
                        `products`.`name` = '$nameEdit', 
                        `products`.`description` = '$descEdit',
                        `products`.`price` = '$priceEdit',
                        `products`.`category_id` = '$categoryEdit',
                        `product_qty`.`qty` = '$quantityEdit'
                     WHERE `products`.`id` = '$id'";
        $mysqli->query($edit_sql);
        echo "<script>window.location.href= 'product_list.php?success=Update Success';</script>";
        exit;
    }
}
?>

<!-- Content body start -->
<div class="content-body">
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">‌ရောင်းရန်ပစ္စည်းများ ပြင်ဆင်ရန်</h3>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">စျေးနှုန်း</label>
                        <input type="text" name="price" class="form-control" value="<?= htmlspecialchars($price) ?>">
                        <small class="text-danger"><?= $price_err ?></small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">အကြောင်းအရာ ဖော်ပြချက်</label>
                        <input type="text" name="description" class="form-control" value="<?= htmlspecialchars($desc) ?>">
                        <small class="text-danger"><?= $desc_err ?></small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">အရေအတွက်</label>
                        <input type="text" name="quantity" class="form-control" value="<?= htmlspecialchars($quantity) ?>">
                        <small class="text-danger"><?= $quantity_err ?></small>
                    </div>

                    <!-- ✅ Category Select -->
                    <div class="form-group">
                        <label class="form-label">အမျိုးအစား ရွေးပါ</label>
                        <select name="category_id" class="form-control">
                            <option value="">ရွေးချယ်ပါ</option>
                            <?php while ($cat = $category_result->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="my-3">
                        <button class="btn btn-primary" type="submit" name="btn_submit">ပြင်ဆင်ပါ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Content body end -->

<?php require '../layouts/footer.php'; ?>