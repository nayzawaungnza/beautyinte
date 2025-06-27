<?php
require '../layouts/header.php';

$error = false;
$name_err =
    $price_err =
    $description_err = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT services.id, services.name, services.price, services.description FROM  `services`";
     
    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
    $price = $oldData['price'];
    $description = $oldData['description'];
}

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    //Name
    if (empty($name)) {
        $error = true;
        $name_err = "Please add name";
    } else if (is_numeric($name)) {
        $error = true;
        $name_err = "name must be number.";
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


    if (!$error) {
        $sql = "UPDATE `services` SET 
        `services`.`name` = '$name', `services`.`price` = '$price', `services`.`description` = '$description'
        WHERE `services`.`id` = '$id'";
        $mysqli->query($sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/service_list.php? success=Update Success' </script>";
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
                <h3>Service Update</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Price</label>
                        <input type="text" name="price" class="form-control" value="<?= $price ?>">
                        <small class="text-danger"><?= $price_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" value="<?= $description ?>"> 
                        <small class="text-danger"><?= $description_err ?></small>
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