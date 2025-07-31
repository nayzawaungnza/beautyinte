<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../staff/task_list.php");
    exit;
}

$id = isset($_GET['id']) ?  $_GET['id'] : '';
if (!$id) {

    $url = $admin_base_url . "product_list.php";
    header("Location: $url");
}
$sql = "SELECT products.id as id, product_qty.qty, products.name,products.img, products.description, products.price 
    FROM `product_qty` INNER JOIN products ON products.id = product_qty.product_id WHERE products.id = '$id'";
$res = $mysqli->query($sql)->fetch_assoc();

require '../layouts/header.php';
?>
<style>
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
        min-height: 100vh;
    }

    .cool-card {
        background: rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 2.5rem 2rem;
        margin-top: 2rem;
        margin-bottom: 2rem;
        transition: box-shadow 0.3s;
        max-width: 800px;
    }

    .cool-card:hover {
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.22);
    }

    .cool-img {
        max-width: 100%;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.13);
        border: 2px solid #fff;
        background: #f8fafc;
    }

    .cool-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }

    .cool-price {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4f46e5;
        margin-bottom: 1rem;
    }

    .cool-desc {
        font-size: 1.1rem;
        color: #374151;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 767px) {
        .cool-card {
            padding: 1.2rem 0.5rem;
        }

        .cool-title {
            font-size: 1.4rem;
        }

        .cool-price {
            font-size: 1.1rem;
        }

        .cool-desc {
            font-size: 1rem;
        }
    }
</style>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>ပစ္စည်း အသေးစိတ်</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'product_list.php' ?>" class="btn btn-dark">
                    ပြန်ရန်
                </a>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-12 d-flex justify-content-center">
                <div class="cool-card w-100">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-5 mb-4 mb-md-0 text-center">
                            <img src="<?= $res['img'] ? '../uplode/' . $res['img'] : '../uplode/default.png'   ?>" alt="Product Image" class="cool-img">
                        </div>
                        <div class="col-12 col-md-7">
                            <div class="cool-title"><?= $res['name'] ?></div>
                            <div class="cool-price"><?= $res['price'] ?> ကျပ်</div>
                            <div class="cool-desc"><b> </b><?= $res['description'] ?></div>
                        </div>
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