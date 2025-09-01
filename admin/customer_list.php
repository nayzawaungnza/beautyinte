<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Get search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build SQL query correctly with AND for search filter
$sql = "SELECT * FROM `users` WHERE role = 'customer'";
if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $sql .= " AND (name LIKE '%$search_escaped%' OR phone LIKE '%$search_escaped%')";
}

$sql .= " ORDER BY created_at ASC";

// Execute the query
$res = $mysqli->query($sql);

// Delete customer if delete_id is provided
$delete_id = isset($_GET['delete_id']) ? intval($_GET['delete_id']) : 0;
if ($delete_id > 0) {
    $delete_res = deleteData('users', $mysqli, "id=$delete_id");
    if ($delete_res) {
        $url = $admin_base_url . "customer_list.php?success=" . urlencode("Delete customer Success");
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
            <h3>ဖောက်သည်စာရင်း</h3>
        </div>
        <div class="col-12 mb-3">
            <form method="GET" class="form-inline d-flex justify-content-end">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by name or phone" value="<?= htmlspecialchars($search) ?>">
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
                                <tr class="text-center">
                                    <th style="color:black">စဉ်</th>
                                    <th style="color:black">အမည်</th>
                                    <th style="color:black">ဆက်သွယ်ရန်ဖုန်း</th>
                                    <th style="color:black">လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($res && $res->num_rows > 0): ?>
                                    <?php $i = 1; ?>
                                    <?php while ($row = $res->fetch_assoc()): ?>
                                        <tr class="text-center">
                                            <td style="color:black"><?= $i++ ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['name']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['phone']) ?></td>
                                            <td>
                                                <a href="./customer_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mx-2">ပြင်ဆင်ရန်</a>
                                                <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">ဖျက်ရန်</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">ဖောက်သည် မတွေ့ပါ</td>
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

<!-- SweetAlert2 and jQuery scripts -->
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
                    window.location.href = 'customer_list.php?delete_id=' + id + <?= json_encode($search !== '' ? '&search=' . urlencode($search) : '') ?>;
                }
            });
        });
    });
</script>

<?php
require '../layouts/footer.php';
?>