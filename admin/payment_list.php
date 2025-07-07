<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Fetch all payments with appointment, customer, and service info
$sql = "SELECT p.id, p.amount, p.payment_method, p.payment_date, a.id as appointment_id, c.name as customer_name, s.name as service_name, a.appointment_date
        FROM payments p
        INNER JOIN appointments a ON p.appointment_id = a.id
        INNER JOIN customers c ON a.customer_id = c.id
        INNER JOIN services s ON a.service_id = s.id
        ORDER BY p.id DESC";
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
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>ငွေပေး‌ချေမှု စာရင်း</h1>

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
                                    <th>နံပါတ်</th>
                                    <th>အချိန်ချိန်းဆိုမှု</th>
                                    <th>ဖောက်သည်</th>
                                    <th>ဝန်ဆောင်မှု</th>
                                    <th>ငွေပမာဏ</th>
                                    <th>အမျိုးအစား</th>
                                    <th>ရက်စွဲ</th>
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
                                            <td>(<?= $row['appointment_id'] ?>) <?= $row['appointment_date'] ?></td>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['amount']) ?></td>
                                            <td><?= htmlspecialchars(ucfirst($row['payment_method'])) ?></td>
                                            <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                            <td>
                                                <div>
                                                    <a href="./payment_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2">ပြင်ဆင်ရန်</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="9" class="text-center">ငွေပေး‌ချေမှု မရှိပါ</td>
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
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
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