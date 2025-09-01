<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error   = isset($_GET['error'])   ? $_GET['error']   : '';
$search  = isset($_GET['search'])  ? trim($_GET['search']) : '';

// Myanmar status text for UI
function getStatusTextMyanmar($status)
{
    switch ($status) {
        case 0:
            return "<span class='badge bg-warning text-dark' style='font-size:16px; font-weight:bold;'>စောင့်နေသည်</span>";
        case 1:
            return "<span class='badge bg-success text-dark' style='font-size:16px; font-weight:bold;'>ပြီးဆုံးသည်</span>";
        case 3:
            return "<span class='badge bg-primary text-dark' style='font-size:16px; font-weight:bold;'>လက်ခံသည်</span>";
        default:
            return "<span class='badge bg-danger text-dark' style='font-size:16px; font-weight:bold;'>ငြင်းပယ်သည်</span>";
    }
}

// Query
$res = "SELECT payments.id AS payment_id, appointments.id, customers.name AS customer_name, staff.name AS staff_name,
        appointments.appointment_date AS app_date, appointments.appointment_time AS app_time, appointments.status, 
        appointments.request, appointments.selected_service_names
        FROM appointments
        INNER JOIN users AS customers ON customers.id = appointments.customer_id
        INNER JOIN users AS staff ON staff.id = appointments.staff_id
        LEFT JOIN payments ON appointments.id = payments.appointment_id";

if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $res .= " WHERE customers.name LIKE '%$search_escaped%'
              OR staff.name LIKE '%$search_escaped%'
              OR appointments.appointment_date LIKE '%$search_escaped%'
              OR appointments.selected_service_names LIKE '%$search_escaped%'";
}

$res .= " ORDER BY appointments.id DESC";
$appointments = $mysqli->query($res);

// Delete appointment
$delete_id = isset($_GET['delete_id']) ? $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('appointments', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "app_list.php?success= အချိန်ချိနိးဆိုမှုဖျက်ခြင်းအောင်မြင်ပါသည်";
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

        <!-- Search Form -->
        <div class="col-12 mb-3">
            <form method="GET" class="form-inline d-flex justify-content-end">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by name or date" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <!-- Alerts -->
        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== '') { ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php } ?>
                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php } ?>
            </div>

            <div class="col-12">
                <!-- Export Buttons -->

                <a href="appointment_export.php<?= $search ? '?search=' . urlencode($search) : '' ?>" class="btn btn-danger mb-3">
                    Export to PDF
                </a>


                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style="color:black">စဉ်</th>
                                    <th style="color:black">ဖောက်သည်အမည်</th>
                                    <th style="color:black">ဝန်ဆောင်မှု အမည်</th>
                                    <th style="color:black">ဝန်ထမ်း အမည်</th>
                                    <th style="color:black">ချိန်းဆိုသည့် ရက်စွဲ</th>
                                    <th style="color:black">ချိန်းဆိုသည့် အချိန်</th>
                                    <th style="color:black">အခြေအနေ</th>
                                    <th style="color:black">တောင်းဆိုမှု</th>
                                    <th style="color:black">လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($appointments && $appointments->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $appointments->fetch_assoc()) { ?>
                                        <tr>
                                            <td style="color:black"><?= $i++ ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['selected_service_names']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['staff_name']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['app_date']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['app_time']) ?></td>
                                            <td style="color:black"><?= getStatusTextMyanmar($row['status']) ?></td>
                                            <td style="color:black"><?= htmlspecialchars($row['request']) ?></td>
                                            <td>
                                                <div>
                                                    <?php if ($row['status'] == 0) { ?>

                                                        <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn mx-2">ဖျက်ရန်</button>
                                                    <?php } elseif ($row['status'] == 1) { ?>
                                                        <?php if ($row['payment_id']) { ?>
                                                            <span class="badge bg-success text-dark mx-2" style="font-size:16px; font-weight:bold;">ငွေပေးချေပြီး</span>
                                                        <?php } else { ?>
                                                            <a href="./payment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-secondary mx-2" style="font-size:16px; font-weight:bold;">ငွေပေးချေရန်</a>
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
</div>

<!-- Delete confirm -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    window.location.href = 'app_list.php?delete_id=' + id
                }
            });
        })
    });
</script>

<?php require '../layouts/footer.php'; ?>