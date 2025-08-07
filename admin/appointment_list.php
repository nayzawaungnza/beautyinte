<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = "SELECT payments.id AS payment_id,appointments.id,customers.name AS customer_name, services.name As service_name, staff.name As staff_name,
appointments.appointment_date AS app_date, appointments.appointment_time AS app_time, appointments.status,
appointments.comment, appointments.request  
FROM `appointments` 
INNER JOIN users AS customers ON customers.id = appointments.customer_id
INNER JOIN users AS staff ON staff.id = appointments.staff_id
INNER JOIN services ON services.id = appointments.service_id
 LEFT JOIN payments ON appointments.id = payments.appointment_id ORDER BY appointments.id DESC ";
$appointments = $mysqli->query($res);

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$res = "SELECT payments.id AS payment_id,appointments.id,customers.name AS customer_name, services.name As service_name, staff.name As staff_name,
appointments.appointment_date AS app_date, appointments.appointment_time AS app_time, appointments.status,
appointments.comment, appointments.request  
FROM `appointments` 
INNER JOIN users AS customers ON customers.id = appointments.customer_id
INNER JOIN users AS staff ON staff.id = appointments.staff_id
INNER JOIN services ON services.id = appointments.service_id
 LEFT JOIN payments ON appointments.id = payments.appointment_id ORDER BY appointments.id DESC ";
if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $res .= " WHERE customers.name LIKE '%$search_escaped%'  OR services.name LIKE '%$search_escaped%'
     OR staff.name LIKE '%$search_escaped%' OR appointments.appointment_date LIKE '%$search_escaped%'";
}

$appointments = $mysqli->query($res);

$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('appointments', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "appointment_list.php?success=Delete Appointment Success";
        header("Location: $url");
        exit;
    }
}
require '../layouts/header.php';
?>
<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>အချိန်ချိန်းဆိုမှု စာရင်း</h3>
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
                <a href="appointment_export.php<?= $search ? '?search=' . urlencode($search) : '' ?>" class="btn btn-success mb-3">
                    Export to CSV
                </a>
                <div class="card">
                    <div class="card-body">

                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>စဉ်</th>
                                    <th>ဖောက်သည်အမည်</th>
                                    <th>ဝန်ဆောင်မှု အမည်</th>
                                    <th>ဝန်ထမ်း အမည်</th>
                                    <th>ချိန်းဆိုသည့် ရက်စွဲ</th>
                                    <th>ချိန်းဆိုသည့် အချိန်</th>
                                    <th>အခြေအနေ</th>
                                    <th>မှတ်ချက်</th>
                                    <th>တောင်းဆိုမှု</th>
                                    <th>လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($appointments && $appointments->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $appointments->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['staff_name']) ?></td>
                                            <td><?= htmlspecialchars($row['app_date']) ?></td>
                                            <td><?= htmlspecialchars($row['app_time']) ?></td>
                                            <td><?php
                                                if ($row['status'] == 0) {
                                                    echo "<span class='badge bg-warning text-dark'>စောင့်နေသည်</span>";
                                                } elseif ($row['status'] == 1) {
                                                    echo "<span class='badge bg-success text-dark'>ပြီးဆုံးသည်</span>";
                                                } elseif ($row['status'] == 3) {
                                                    echo "<span class='badge bg-primary text-dark'>လက်ခံသည်</span>";
                                                } else {
                                                    echo "<span class='badge bg-danger text-dark'>ငြင်းပယ်သည်</span>";
                                                }
                                                ?></td>
                                            <td><?= htmlspecialchars($row['comment']) ?></td>
                                            <td><?= htmlspecialchars($row['request']) ?></td>
                                            <td>
                                                <div>
                                                    <?php if ($row['status'] == 0) {  ?>
                                                        <a href="./appointment_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2 mb-2">ပြင်ဆင်ရန်</a>
                                                        <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn mx-2">ဖျက်ရန်</button>
                                                    <?php } else if ($row['status'] == 3) { ?>

                                                    <?php } else if ($row['status'] == 1) { ?>

                                                        <?php
                                                        if ($row['payment_id']) { ?>
                                                            <!-- Payment already exists -->
                                                            <span class="badge bg-success text-dark mx-2">ငွေပေးချေပြီး</span>
                                                        <?php } else { ?>
                                                            <!-- No payment yet -->
                                                            <a href="./payment_create.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary edit_btn mx-2">ငွေပေးချေရန်</a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="10" class="text-center">အချိန်ချိန်းဆိုမှု မရှိသေးပါ။</td>
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
                    window.location.href = 'appointment_list.php?delete_id=' + id
                }
            });
        })
    })
</script>
<?php
require '../layouts/footer.php';
?>