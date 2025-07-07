<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = "SELECT products.id,products.name, products.description, products.price,product_qty.qty AS quantity FROM `product_qty` INNER JOIN products ON products.id = product_qty.product_id";
$products = $mysqli->query($res);
// $sql = "SELECT products.*, categories.name AS category_name, discounts.percent
//         FROM products
//         LEFT JOIN categories ON categories.id = products.category_id
//         LEFT JOIN discounts ON discounts.id = products.discount_id
//         ";
// $res = $mysqli->query($sql);



$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('products', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "product_list.php?success=Delete Product Success";
        header("Location: $url");
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>‌ရောင်းရန်ပစ္စည်းများ စာရင်း</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'product_create.php' ?>" class="btn btn-primary">
                    ‌ရောင်းရန်ပစ္စည်းများ အသစ်ဖန်တီးရန်
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== '') { ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php } ?>
                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="">စဉ်</th>
                                    <th class="">အမည်</th>
                                    <th class="">အကြောင်းအရာဖော်ပြချက်</th>
                                    <th class="">စျေးနှုန်း</th>
                                    <th class="">ပစ္စည်းအရေအတွက်</th>
                                    <th class="">လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($products->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $products->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['description'] ?></td>
                                            <td><?= $row['price'] ?></td>
                                            <td><?= $row['quantity'] ?></td>
                                            <td>
                                                <div>
                                                    <a href="<?= $admin_base_url . 'product_details.php?id=' . $row['id'] ?>" class="btn btn-sm btn-primary mx-2">အသေးစိတ်</a>
                                                    <a href="./product_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2">ပြင်ဆင်ရန်</a>
                                                    <a href="./product_image_update.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mx-2">ပုံများ</a>
                                                    <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn mx-2">ဖျက်ရန်</button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
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
<script>
    $(document).ready(function() {
        $('.delete_btn').click(function() {
            console.log('click');
            const id = $(this).data('id')
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'product_list.php?delete_id=' + id
                }
            });
        })
    })
</script>
<?php
require '../layouts/footer.php';
?>