<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = "SELECT * FROM `users` ";
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
<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>အသုံးပြုသူ စာရင်း</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'user_create.php' ?>" class="btn btn-primary">
                    အသုံးပြုသူ အသစ်ဖန်တီးရန်
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
                                    <th class="">နံပါတ်</th>
                                    <th class="">ပရိုဖိုင်</th>
                                    <th class="">အမည်</th>
                                    <th class="">အီးမေးလ်</th>
                                    <th class="">အခန်းကဏ္ဍ</th>
                                    <th class="">ဆက်သွယ်ရန်ဖုန်း</th>
                                    <th class="">လိင်</th>
                                    <th class="">လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($users->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $users->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <img src="<?= $row['image'] ? '../uplode/' . $row['image'] : '../uplode/default.png' ?>"
                                                    alt="Profile Image" class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                                            </td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <td><?= $row['role'] ?></td>
                                            <td><?= $row['phone'] ?></td>
                                            <td><?= $row['gender'] == "male" ? "ကျား" : "မ" ?></td>
                                            <td>
                                                <a href="./user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2">ပြင်ဆင်ရန်</a>
                                                <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">ဖျက်ရန်</button>
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
                title: 'ဖျက်မည်ဆိုတာသေချာပြီလား',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ဖျက်မည်',
                cancelButtonText: 'မဖျက်ပါ'
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