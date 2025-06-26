<?php

require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = selectData('customers', $mysqli, "", "*", "ORDER BY created_at DESC");

// $sql = "SELECT products.*, categories.name AS category_name, discounts.percent
//         FROM products
//         LEFT JOIN categories ON categories.id = products.category_id
//         LEFT JOIN discounts ON discounts.id = products.discount_id
//         ";
// $res = $mysqli->query($sql);



$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('customer', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "customer_list.php?success=Delete customer Success";
        header("Location: $url");
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>Customer List</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'customer_create.php' ?>" class="btn btn-primary">
                    Customer Create
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
                                <tr class="text-center">
                                    <th class="">No.</th>
                                    <th class="">Name</th>
                                    <th class="">Phone</th>
                                    <th class="">Password</th>
                                    <th class="">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($res->num_rows > 0) {
                                    while ($row = $res->fetch_assoc()) { ?>
                                        <tr class="text-center">
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['phone'] ?></td>
                                            <td><?= $row['password'] ?></td>


                                            <td>
                                                <button data-id=" <?= $row['id'] ?>" class="btn btn-sm btn-primary edit_btn">Edit</button>
                                                <button data-id=" <?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">Delete</button>
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
                    window.location.href = 'customer_list.php?delete_id=' + id
                }
            });
        })
    })
</script>
<?php
require '../layouts/footer.php';
?>