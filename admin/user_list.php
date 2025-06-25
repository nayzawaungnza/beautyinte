<?php

require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = "SELECT users.id, users.name, users.email, users.role, users.gender FROM `users` ";
$users = $mysqli->query($res);




$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('users', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "user_list.php?success=Delete User Success";
        header("Location: $url");
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>User List</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'user_create.php' ?>" class="btn btn-primary">
                    Create User
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
                                    <th class="">No.</th>
                                    <th class="">Email</th>
                                    <th class="">Role</th>
                                    <th class="">Gender</th>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($users->num_rows > 0) {
                                    while ($row = $users->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['role'] ?></td>
                                            <td><?= $row['gender'] ?></td>
                                            <td>
                                                <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">Delete</button>
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
                    window.location.href = 'user_list.php?delete_id=' + id
                }
            });
        })
    })
</script>
<?php
require '../layouts/footer.php';
?>