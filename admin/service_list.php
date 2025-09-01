<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query with category join
$sql = "SELECT services.*, service_categories.name AS category_name 
        FROM services 
        LEFT JOIN service_categories ON services.category_id = service_categories.id";

if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $sql .= " WHERE services.name LIKE '%$search_escaped%'";
}
$res = $mysqli->query($sql);

// Delete logic
$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('services', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "service_list.php?success=ဝန်ဆောင်မှုကိုအောင်မြင်စွာဖျက်လိုက်ပါသည်";
        header("Location: $url");
    }
}

require '../layouts/header.php';
?>

<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>ဝန်ဆောင်မှုစာရင်း</h3>
            <div>
                <a href="<?= $admin_base_url . 'service_create.php' ?>" class="btn btn-primary">
                    ဝန်ဆောင်မှု အသစ်ဖန်တီးရန်
                </a>
            </div>
        </div>

        <div class="col-12 mb-3">
            <form method="GET" class="form-inline d-flex justify-content-end">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== '') { ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php } ?>
                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php } ?>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr class="text-center">
                                    <th style="color:black">စဉ်</th>
                                    <th style="color:black">ဝန်ဆောင်မှုပုံ</th>
                                    <th style="color:black">ဝန်ဆောင်မှုအမည်</th>
                                    <th style="color:black">အမျိုးအစား</th>
                                    <th style="color:black">စျေးနှုန်း</th>
                                    <th style="color:black">ကြာချိန်</th>
                                    <th style="color:black">အကြောင်းအရာ ဖော်ပြချက်</th>
                                    <th style="color:black">လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($res && $res->num_rows > 0):
                                    $i = 1;
                                    while ($row = $res->fetch_assoc()): ?>
                                        <tr class="text-center">
                                            <td style="color:black"><?= $i++ ?></td>
                                            <td>
                                                <img src="<?= $row['image'] ? '../uplode/' . $row['image'] : '../uplode/default.png' ?>"
                                                    alt="Profile Image"
                                                    class="img-fluid rounded-circle"
                                                    style="width: 50px; height: 50px;">
                                            </td>
                                            <td style="color:black"><?= htmlspecialchars($row['name']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['category_name'] ?? 'မသတ်မှတ်ရသေးပါ') ?></td>
                                            <td style="color:black"><?= number_format($row['price']) ?> ကျပ်</td>
                                            <td style="color:black"><?= number_format($row['time']) ?> မိနစ်</td>
                                            <td style="color:black"><?= htmlspecialchars($row['description']) ?></td>
                                            <td>
                                                <a href="./service_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success mx-2 mb-2">ပြင်ဆင်ရန်</a>
                                                <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">ဖျက်ရန်</button>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr class="text-center">
                                        <td colspan="7" class="p-4">ဒေတာ မရှိပါ။</td>
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

<!-- Delete confirmation script -->
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
                    window.location.href = 'service_list.php?delete_id=' + id
                }
            });
        });
    });
</script>

<?php require '../layouts/footer.php'; ?>