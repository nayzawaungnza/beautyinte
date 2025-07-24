<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name =
    $price =
    $description =
    $name_err =
    $price_err =
    $description_err = '';
$image_err = '';
$image = '';
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
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } else if (strlen($name) >= 1000) {
        $error = true;
        $name_err = "အမည်သည် စာလုံး ၁၀၀၀ ထက်နည်းရပါမည်။";
    }
    //Price

    if (empty($price)) {
        $error = true;
        $price_err = "ကျေးဇူးပြု၍ ဈေးနှုန်းထည့်ပါ။";
    } else if (!is_numeric($price)) {
        $error = true;
        $price_err = "ဈေးနှုန်းသည် ဂဏန်းဖြစ်ရပါမည်။";
    }

    // Image
    if (isset($_FILES['image'])) {
        $target_dir = "../uplode/";
        $file_name = basename($_FILES['image']['name']);
        $target_file =  time() . '_' . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $target_file)) {
                $image = $target_file;
            } else {
                $error = true;
                $image_err = "ဓာတ်ပုံတင်ရန် မအောင်မြင်ပါ။";
            }
        } else {
            $error = true;
            $image_err = "ဓာတ်ပုံအမျိုးအစား မမှန်ပါ။";
        }
    } else {
        $image = '';
    }

    //description
    if (empty($description)) {
        $error = true;
        $description_err = "ကျေးဇူးပြု၍ ဖော်ပြချက်ထည့်ပါ။";
    } else if (strlen($description) > 1000) {
        $error = true;
        $description_err = "ဖော်ပြချက်သည် စာလုံး ၁၀၀ ထက်နည်းရပါမည်။";
    }


    if (!$error) {
        $sql = "INSERT INTO `services`(`name`, `description`, `price`, `image`)
         VALUES ('$name','$description','$price','$image')";
        $mysqli->query($sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/service_list.php' </script>";
    }
}


?>

<!-- Content body start -->

<div class="content-body">



    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">ဝန်ဆောင်မှု အသစ်ဖန်တီးရန်</h3>
                <form method="POST" enctype="multipart/form-data">
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
                        <label for="image" class="form-label">ပုံထည့်ပါ</label>
                        <input type="file" name="image" class="form-control">
                        <small class="text-danger"><?= isset($image_err) ? $image_err : '' ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">အကြောင်းအရာ ဖော်ပြချက်</label>
                        <input type="text" name="description" class="form-control" value="<?= $description ?>">
                        <small class="text-danger"><?= $description_err ?></small>
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