<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM `users` WHERE role != 'customer'";
if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $sql .= " AND (name LIKE '%$search_escaped%' OR email LIKE '%$search_escaped%' OR phone LIKE '%$search_escaped%' OR role LIKE '%$search_escaped%')";
}
$users = $mysqli->query($sql);

// Handle delete
$delete_id = $_GET['delete_id'] ?? '';
if ($delete_id !== '') {
    $res = deleteData('users', $mysqli, "id=$delete_id");
    if ($res) {
        header("Location: {$admin_base_url}user_list.php?success=Delete User Success");
    }
}

require '../layouts/header.php';
?>

<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>ဝန်ထမ်းများ စာရင်း</h3>
            <a href="<?= $admin_base_url . 'user_create.php' ?>" class="btn btn-primary">
                ဝန်ထမ်းများ အသစ်ဖန်တီးရန်
            </a>
        </div>
        <div class="col-12 mb-3">
            <form method="get" class="form-inline d-flex justify-content-end">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by name, email, phone or role" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success) { ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php } ?>
                <?php if ($error) { ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php } ?>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>စဉ်</th>
                                    <th>ပရိုဖိုင်</th>
                                    <th>အမည်</th>
                                    <th>အီးမေးလ်</th>
                                    <!-- <th>အခန်းကဏ္ဍ</th> -->
                                    <th>ဆက်သွယ်ရန်ဖုန်း</th>
                                    <th>လိင်</th>
                                    <th>ရာထူး</th>
                                    <th>လစာ</th>
                                    <th>လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($users->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $users->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td>
                                                <img src="<?= $row['image'] ? '../uplode/' . $row['image'] : '../uplode/default.png' ?>"
                                                    alt="Profile Image"
                                                    class="img-fluid rounded-circle"
                                                    style="width: 50px; height: 50px;">
                                            </td>
                                            <td><?= $row['name'] ?></td>
                                            <td><?= $row['email'] ?></td>
                                            <!-- <td><?= $row['role'] == "admin" ? "အုပ်ချုပ်သူ" : "ဝန်ထမ်း" ?></td> -->
                                            <td><?= $row['phone'] ?></td>
                                            <td><?= $row['gender'] == "male" ? "ကျား" : "မ" ?></td>
                                            <td><?= $row['position'] ?: '-' ?></td>
                                            <td><?= $row['salary'] ? number_format($row['salary']) . " ကျပ်" : '-' ?></td>
                                            <td>
                                                <a href="./user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mx-2">ပြင်ဆင်ရန်</a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="10" class="text-center">ဝန်ထမ်းများ မရှိသေးပါ။</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete confirmation -->
<script>
    $(document).ready(function() {
        $('.delete_btn').click(function() {
            const id = $(this).data('id');
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
                    window.location.href = 'user_list.php?delete_id=' + id;
                }
            });
        });
    });
</script>

<?php require '../layouts/footer.php'; ?>