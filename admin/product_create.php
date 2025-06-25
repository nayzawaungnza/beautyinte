<?php
require '../layouts/header.php';

$error = false;
$name_err =
    $price_err =
    $description_err =
    $quantity_err   = '';

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
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
    if (empty($description)) {
        $error = true;
        $description_err = "Please add description";
    } else if (strlen($description) > 100) {
        $error = true;
        $description_err = "Description must be less than 100.";
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
        $sql = "INSERT INTO `products`(`name`, `description`, `price`)
        VALUES ('$name','$description','$price')";
        if($mysqli->query($sql)){
            $insert_id = $mysqli->insert_id;
            $qty_sql = "INSERT INTO `product_qty`(`product_id`, `qty`)
        VALUES ('$insert_id','$quantity')";
        $mysqli->query($qty_sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/product_list.php' </script>";
    }
}
}


?>

<!-- Content body start -->

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>Create Product</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Price</label>
                        <input type="text" name="price" class="form-control">
                        <small class="text-danger"><?= $price_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control">
                        <small class="text-danger"><?= $description_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Quantity</label>
                        <input type="text" name="quantity" class="form-control">
                        <small class="text-danger"><?= $quantity_err ?></small>
                    </div>
                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">Submit</button>
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