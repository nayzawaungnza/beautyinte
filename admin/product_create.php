<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name = $price = $desc = $quantity = $category_id = '';
$name_err = $price_err = $description_err = $quantity_err = $file_err = $category_err = '';

// ✅ Fetch categories for select box
$categories = $mysqli->query("SELECT * FROM product_categories");

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $category_id = $_POST['category'];
    $profile = $_FILES['file_name'];
    $tmp_name = $profile['tmp_name'];


    // ✅ Validations

    // Name
    if (empty($name)) {
        $error = true;
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } else if (strlen($name) < 5) {
        $error = true;
        $name_err = "အမည်သည် အနည်းဆုံး စာလုံး ၅ လုံး ပြည့်မီရပါမည်။";
    } else if (strlen($name) >= 100) {
        $error = true;
        $name_err = "အမည်သည် စာလုံး ၁၀၀ ထက်နည်းရပါမည်။";
    }

    // Price
    if (empty($price)) {
        $error = true;
        $price_err = "ကျေးဇူးပြု၍ ဈေးနှုန်းထည့်ပါ။";
    } else if (!is_numeric($price)) {
        $error = true;
        $price_err = "ဈေးနှုန်းသည် ဂဏန်းဖြစ်ရပါမည်။";
    } else if ($price > 1000000) {
        $error = true;
        $price_err = "ဈေးနှုန်းသည် ၁,၀၀၀,၀၀၀ ကျပ်အောက်ဖြစ်ရပါမည်။";
    }

    // Description
    if (empty($description)) {
        $error = true;
        $description_err = "ကျေးဇူးပြု၍ ဖော်ပြချက်ထည့်ပါ။";
    } else if (strlen($description) > 1000) {
        $error = true;
        $description_err = "ဖော်ပြချက်သည် စာလုံး ၁၀၀၀ ထက်နည်းရပါမည်။";
    }

    // Quantity
    if (empty($quantity)) {
        $error = true;
        $quantity_err = "ကျေးဇူးပြု၍ အရေအတွက်ထည့်ပါ။";
    } else if (!is_numeric($quantity)) {
        $error = true;
        $quantity_err  = "Quantity must be number.";
    } else if ($quantity > 1000) {
        $error = true;
        $quantity_err  = "Quantity limited.";
    }

    // Category
    if (empty($category_id)) {
        $error = true;
        $category_err = "ကျေးဇူးပြု၍ အမျိုးအစားရွေးပါ။";
    }

    // Image Upload
    $folder = "../uplode/";
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    $fileName = uniqid() . $profile['name'];
    move_uploaded_file($tmp_name, $folder . $fileName);

    // ✅ Insert into DB
    if (!$error) {
        $sql = "INSERT INTO `products`(`name`, `description`, `price`, `img`, `category_id`)
                VALUES ('$name','$description','$price', '$fileName', '$category_id')";
        if ($mysqli->query($sql)) {
            $insert_id = $mysqli->insert_id;
            $qty_sql = "INSERT INTO `product_qty`(`product_id`, `qty`)
                        VALUES ('$insert_id','$quantity')";
            $mysqli->query($qty_sql);
            echo "<script>window.location.href= 'http://localhost/Beauty/admin/product_list.php'</script>";
        }
    }
}
?>

<!-- Content body start -->

<div class="content-body">
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">‌ရောင်းရန်ပစ္စည်းများ အသစ်ဖန်တီးပါ</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">အမည်</label>
                                <input type="text" name="name" class="form-control" value="<?= $name ?>">
                                <small class="text-danger"><?= $name_err ?></small>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">စျေးနှုန်း</label>
                                <input type="text" name="price" class="form-control" value="<?= $price ?>">
                                <small class="text-danger"><?= $price_err ?></small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">ဖော်ပြချက်</label>
                                <input type="text" name="description" class="form-control" value="<?= $desc ?>">
                                <small class="text-danger"><?= $description_err ?></small>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">အရေအတွက်</label>
                                <input type="text" name="quantity" class="form-control" value="<?= $quantity ?>">
                                <small class="text-danger"><?= $quantity_err ?></small>
                            </div>
                        </div>

                        <!-- ✅ Category Select -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">အမျိုးအစား</label>
                                <select name="category" class="form-control">
                                    <option value="">-- အမျိုးအစားရွေးပါ --</option>
                                    <?php while ($row = $categories->fetch_assoc()) : ?>
                                        <option value="<?= $row['id'] ?>" <?= ($row['id'] == $category_id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($row['name']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <small class="text-danger"><?= $category_err ?></small>
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">ပုံ</label>
                                <input type="file" name="file_name" class="form-control">
                                <small class="text-danger"><?= $file_err ?></small>
                            </div>

                            <div class="my-2">
                                <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Content body end -->

<?php require '../layouts/footer.php'; ?>