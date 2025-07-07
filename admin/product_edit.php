<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name_err =
    $price_err =
    $desc_err =
    $quantity_err   = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT products.id as id, product_qty.qty, products.name, products.description, products.price 
    FROM `product_qty` INNER JOIN products ON products.id = product_qty.product_id WHERE products.id = '$id'";
    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
    $price = $oldData['price'];
    $desc = $oldData['description'];
    $quantity = $oldData['qty'];
}

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $nameEdit = $_POST['name'];
    $priceEdit = $_POST['price'];
    $descEdit = $_POST['description'];
    $quantityEdit = $_POST['quantity'];
    //Name
    if (empty($name)) {
        $error = true;
        $name_err = "Please add name";
    } else if (strlen($name) < 5) {
        $error = true;
        $name_err = "Name must be fill greater than 5.";
    } else if (strlen($name) >= 100) {
        $error = true;
        $name_err = "Name must be fill less than 100.";
    }
    //Price

    if (empty($price)) {
        $error = true;
        $price_err = "Please add price";
    } else if (!is_numeric($price)) {
        $error = true;
        $price_err = "Price must be number.";
    } else if ($price > 1000000) {
        $error = true;
        $price_err = "Price must be under 1000000.";
    }
    //description
    if (empty($desc)) {
        $error = true;
        $desc_err = "Please add description";
    } else if (strlen($desc) > 100) {
        $error = true;
        $desc_err = "Description must be less than 100.";
    }

    // quantity
    if (empty($quantity)) {
        $error = true;
        $quantity_err = "Please add quantity";
    } else if (!is_numeric($quantity)) {
        $error = true;
        $quantity_err  = "Quantity must be number.";
    }
    if (!$error) {
        $edit_sql = "UPDATE `product_qty` INNER JOIN `products` ON `products`.`id` = `product_qty`.`product_id` SET 
        `products`.`name` = '$nameEdit', `products`.`description` = '$descEdit' , `products`.`price` = '$priceEdit', `product_qty`.`qty` = '$quantityEdit'
        WHERE `products`.`id` = '$id'";
        $mysqli->query($edit_sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/product_list.php? success=Update Success' </script>";
    }
}



?>

<!-- Content body start -->

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">အလှပြင်ဆိုင် စနစ်အနှစ်ချုပ်မျက်နှာပြင်</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">ပင်မစာမျက်နှာ</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>‌ရောင်းရန်ပစ္စည်းများ အသစ်ဖန်တီးရန်</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">စျေးနှုန်း</label>
                        <input type="text" name="price" class="form-control" value="<?= $price ?>">
                        <small class="text-danger"><?= $price_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">အကြောင်းအရာ ဖော်ပြချက်</label>
                        <input type="text" name="description" class="form-control" value="<?= $desc ?>">
                        <small class="text-danger"><?= $desc_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">အရေအတွက်</label>
                        <input type="text" name="quantity" class="form-control" value="<?= $quantity ?>">
                        <small class="text-danger"><?= $quantity_err ?></small>
                    </div>
                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>

<!-- Content body end -->



<?php

require '../layouts/footer.php';

?>