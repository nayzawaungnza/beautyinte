<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name_err =
    $phone_err =
    $name =
    $phone = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT customers.id, customers.name, customers.phone  FROM  `customers`";

    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
    $phone = $oldData['phone'];
}

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];

    //Name
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
    //phone
    if (empty($phone)) {
        $error = true;
        $phone_err = "ကျေးဇူးပြုပြီး ဖုန်းနံပါတ် ထည့်ပါ။";
    } else if (strlen($phone) < 11) {
        $error = true;
        $phone_err = "ဖုန်းနံပါတ်သည် ဂဏန်း (၁၁) လုံးထက် များရမည်။";
    }



    if (!$error) {
        $sql = "INSERT INTO `customers`(`name`, `phone`)
         VALUES ('$name','$phone')";
        $mysqli->query($sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/customer_list.php' </script>";
    }
}


?>

<!-- Content body start -->

<div class="content-body">

    <!-- <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">အလှပြင်ဆိုင် စနစ်အနှစ်ချုပ်မျက်နှာပြင်</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">ပင်မစာမျက်နှာ</a></li>
            </ol>
        </div>
    </div> -->
    <!-- row -->

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">ဖောက်သည်အသစ်ဖန်တီးရန်</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">ဆက်သွယ်ရန်ဖုန်း</label>
                        <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                        <small class="text-danger"><?= $phone_err ?></small>
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