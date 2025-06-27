<?php
require '../layouts/header.php';

$error = false;
$name_err =
    $phone_err =
    $password_err =
    $name =
    $phone =
    $password = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT customers.id, customers.name, customers.phone, customers.password FROM  `customers`";
     
    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
    $phone = $oldData['phone'];
    $password = $oldData['password'];
}

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];


    //Name
    if (empty($name)) {
        $error = true;
        $name_err = "Please add name";
    } else if (strlen($name) < 5) {
        $error = true;
        $name_err = "Name must be fill greater than 5.";
    } else if (strlen($name) >= 10) {
        $error = true;
        $name_err = "Name must be fill less than 100.";
    }
    //phone
    if (empty($phone)) {
        $error = true;
        $phone_err = "Please add phone";
    } else if (strlen($phone) < 11) {
        $error = true;
        $phone_err = "Phone must be fill greater than 11.";
    }
    //Password
    if (strlen($password) === 0) {
        $error = true;
        $password_err = "Please fill Password";
    } else if (strlen($password) < 8) {
        $error = true;
        $password_err = "Password must be greater than 8.";
    } else if (strlen($password) > 30) {
        $error = true;
        $password_err = "Password must be less than 30.";
    } else {
        $byScriptPassword = md5($password);
    }


    if (!$error) {
        $sql = "UPDATE `customers` SET 
        `customers`.`name` = '$name', `customers`.`phone` = '$phone', `customers`.`password` = '$password'
        WHERE `customers`.`id` = '$id'";
        $mysqli->query($sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/customer_list.php' </script>";
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
                <h3>Customer Update</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                        <small class="text-danger"><?= $phone_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" value="<?= $password ?>">
                        <small class="text-danger"><?= $password_err ?></small>
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