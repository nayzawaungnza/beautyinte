<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Fetch all payments with appointment, customer, and service info
// $sql = "SELECT p.id, a.id as appointment_id, c.name as customer_name, s.name as service_name,
//  p.amount, p.payment_date, pm.name as payment_method_name FROM payments p
//   INNER JOIN appointments a ON p.appointment_id = a.id
//    INNER JOIN users c ON a.customer_id = c.id INNER JOIN services s ON a.service_id = s.id 
//    INNER JOIN payment_method pm ON p.payment_method_id = pm.id ORDER BY p.id DESC";
$sql = "SELECT 
    p.id, 
    a.id AS appointment_id, 
    c.name AS customer_name, 
    s.name AS service_name,
    p.amount, 
    p.payment_date, 
    pm.name AS payment_method_name,
    pm.user_acc, 
    pm.ph_no
FROM payments p
INNER JOIN appointments a ON p.appointment_id = a.id
INNER JOIN users c ON a.customer_id = c.id
INNER JOIN services s ON a.service_id = s.id
INNER JOIN payment_method pm ON p.payment_method_id = pm.id
ORDER BY p.id DESC";

$payments = $mysqli->query($sql);


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT p.id, a.id as appointment_id, c.name as customer_name, s.name as service_name,
 p.amount, p.payment_date , pm.name as payment_method_name FROM payments p
  INNER JOIN appointments a ON p.appointment_id = a.id
   INNER JOIN users c ON a.customer_id = c.id INNER JOIN services s ON a.service_id = s.id 
   INNER JOIN payment_method pm ON p.payment_method_id = pm.id";
if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $sql .= " WHERE c.name LIKE '%$search_escaped%' OR p.payment_date LIKE '%$search_escaped%'
     OR pm.name LIKE '%$search_escaped%'";
}
$payments = $mysqli->query($sql);
// Handle delete
$delete_id = isset($_GET['delete_id']) ? $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = $mysqli->query("DELETE FROM payments WHERE id = $delete_id");
    if ($res) {
        $url = $admin_base_url . "payment_list.php?success=Delete Payment Success";
        header("Location: $url");
        exit;
    }
}
require '../layouts/header.php';
?>
<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3 class="text-center mb-2 text-info">ငွေပေး‌ချေမှု စာရင်း</h3>
        </div>
        <div class="col-12 mb-3">
            <form method="GET" class="form-inline d-flex justify-content-end">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by name or date" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
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
                                    <th>စဉ်</th>
                                    <th>ဖောက်သည်အမည်</th>
                                    <th>ဝန်ဆောင်မှုအမည်</th>
                                    <th>ငွေပမာဏ</th>
                                    <th>ငွေပေးချေမှုနည်းလမ်း</th>
                                    <th>အကောင့်</th>
                                    <th>ဖုန်း</th>
                                    <th>ငွေပေးချေသည့်ရက်စွဲ</th>
                                    <th>လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($payments && $payments->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $payments->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['amount']) ?> ကျပ်</td>
                                            <td><?= htmlspecialchars($row['payment_method_name']) ?></td>
                                            <td><?= isset($row["user_acc"]) ? htmlspecialchars($row["user_acc"]) : '' ?></td>
                                            <td><?= isset($row["ph_no"]) ? htmlspecialchars($row["ph_no"]) : '' ?></td>
                                            <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                            <td>
                                                <a href="payment_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2">ပြင်ဆင်ရန်</a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center">ငွေပေးချေမှုများ မရှိသေးပါ</td>
                                    </tr>
                                <?php } ?>
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
                    window.location.href = 'payment_list.php?delete_id=' + id
                }
            });
        })
    })
</script>
<?php
require '../layouts/footer.php';
?>