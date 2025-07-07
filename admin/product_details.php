<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
require '../require/check_auth.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../staff/task_list.php");
    exit;
}

$id = isset($_GET['id']) ?  $_GET['id'] : '';
if (!$id) {

    $url = $admin_base_url . "product_list.php";
    header("Location: $url");
}
$sql = "SELECT products.id as id, product_qty.qty, products.name, products.description, products.price 
    FROM `product_qty` INNER JOIN products ON products.id = product_qty.product_id WHERE products.id = '$id'";
$res = $mysqli->query($sql)->fetch_assoc();

require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>ပစ္စည်းစျေးနှုန်းများ အသေးစိတ်</h1>
            <div class="">
                <a href="<?= $admin_base_url . 'product_list.php' ?>" class="btn btn-dark">
                    ပြန်ရန်
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h1><?= $res['name'] ?></h1>
                        <small><?= $res['price'] ?></small>
                        <p><b>Description: </b><?= $res['description'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>
<!--**********************************
            Content body end
        ***********************************-->

<?php
require '../layouts/footer.php';
?>