<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Get search term from GET parameter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build base SQL with role filtering (exclude 'customer' and 'admin')
$sql = "SELECT * FROM `users` WHERE role != 'customer' AND role != 'admin'";

// Add search filtering if search term is not empty
if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $sql .= " AND (name LIKE '%$search_escaped%' OR email LIKE '%$search_escaped%' OR phone LIKE '%$search_escaped%' OR role LIKE '%$search_escaped%')";
}

$sql .= " ORDER BY role ASC";

// Execute query
$res = $mysqli->query($sql);

// Delete user if delete_id is set
$delete_id = isset($_GET['delete_id']) ? intval($_GET['delete_id']) : 0;
if ($delete_id > 0) {
    $delete_res = deleteData('users', $mysqli, "id=$delete_id");
    if ($delete_res) {
        $url = $admin_base_url . "user_list.php?success=" . urlencode("အောင်မြင်စွာဖျက်ပြီးပါပြီ");
        header("Location: $url");
        exit();
    } else {
        $error = "Delete failed!";
    }
}

require '../layouts/header.php';
?>

<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>ဝန်ထမ်း စာရင်း</h3>
            <div>
                <a href="<?= $admin_base_url . 'user_create.php' ?>" class="btn btn-primary">ဝန်ထမ်း အသစ်ဖန်တီးရန်</a>
            </div>
        </div>

        <div class="col-12 mb-3">
            <form method="get" class="form-inline d-flex justify-content-end">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by name, email, or phone" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== ''): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if ($error !== ''): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style="color:black">နံပါတ်</th>
                                    <th style="color:black">ပရိုဖိုင်</th>
                                    <th style="color:black">အမည်</th>
                                    <th style="color:black">အီးမေးလ်</th>
                                    <th style="color:black">အခန်းကဏ္ဍ</th>
                                    <th style="color:black">ဆက်သွယ်ရန်ဖုန်း</th>
                                    <th style="color:black">လိင်</th>
                                    <th style="color:black">လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($res && $res->num_rows > 0): ?>
                                    <?php $i = 1; ?>
                                    <?php while ($row = $res->fetch_assoc()): ?>
                                        <tr>
                                            <td style="color:black"><?= $i++ ?></td>
                                            <td>
                                                <img src="<?= $row['image'] ? '../uplode/' . htmlspecialchars($row['image']) : '../uplode/default.png' ?>" alt="Profile Image" class="img-fluid rounded-circle" style="width: 50px; height: 50px;">
                                            </td>
                                            <td style="color:black"><?= htmlspecialchars($row['name']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['email']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['role']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['phone']) ?></td>
                                            <td style="color:black"><?= $row['gender'] === "male" ? "ကျား" : "မ" ?></td>
                                            <td>
                                                <a href="./user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mx-2">ပြင်ဆင်ရန်</a>
                                                <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">ဖျက်ရန်</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">User မတွေ့ပါ</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    window.location.href = 'user_list.php?delete_id=' + id + (<?= json_encode($search !== '' ? "&search=" . urlencode($search) : "") ?>);
                }
            });
        });
    });
</script>

<?php
require '../layouts/footer.php';
?>