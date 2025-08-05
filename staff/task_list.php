<?php
require '../require/check_auth.php';
checkAuth('staff');
require '../require/db.php';
require '../require/common.php';
require '../layouts/header.php';

// Assume staff is logged in and their user id is in session
$staff_id = $_SESSION['user_id'] ?? 0;

// Handle status update actions
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if ($action === 'accept') {
        $mysqli->query("UPDATE appointments SET status=3 WHERE id=$id AND staff_id=$staff_id");
    } elseif ($action === 'reject') {
        $mysqli->query("UPDATE appointments SET status=2 WHERE id=$id AND staff_id=$staff_id");
    } elseif ($action === 'complete') {
        $mysqli->query("UPDATE appointments SET status=1 WHERE id=$id AND staff_id=$staff_id");
    }
    echo "<script>window.location.href='task_list.php';</script>";
    exit;
}

// Fetch appointments assigned to this staff member
$sql = "SELECT appointments.id,customers.name AS customer_name, services.name As service_name, staff.name As staff_name,
appointments.appointment_date AS app_date, appointments.appointment_time AS app_time, appointments.status,
appointments.comment, appointments.request  
FROM `appointments` 
INNER JOIN users AS customers ON customers.id = appointments.customer_id
INNER JOIN users AS staff ON staff.id = appointments.staff_id
INNER JOIN services ON services.id = appointments.service_id
        WHERE staff.id = $staff_id
        ORDER BY appointments.appointment_date DESC, appointments.appointment_time DESC";
$appointments = $mysqli->query($sql);
?>
<div class="content-body">
    <div class="container-fluid">

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>ဖောက်သည်</th>
                                    <th>ဝန်ဆောင်မှု</th>
                                    <th>ရက်စွဲ</th>
                                    <th>အချိန်</th>
                                    <th>အခြေအနေ</th>
                                    <th>လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($appointments && $appointments->num_rows > 0): ?>
                                    <?php while ($row = $appointments->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['app_date']) ?></td>
                                            <td><?= htmlspecialchars($row['app_time']) ?></td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 0) {
                                                    echo "<span class='badge bg-warning'>စောင့်နေသည်</span>";
                                                } elseif ($row['status'] == 3) {
                                                    echo "<span class='badge bg-info'>လက်ခံသည်</span>";
                                                } elseif ($row['status'] == 1) {
                                                    echo "<span class='badge bg-success'>ပြီးဆုံးသည်</span>";
                                                } elseif ($row['status'] == 2) {
                                                    echo "<span class='badge bg-danger'>ငြင်းပယ်သည်</span>";
                                                } else {
                                                    echo "<span class='badge bg-secondary'>Unknown</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == 0): // Pending 
                                                ?>
                                                    <a href="?action=accept&id=<?= $row['id'] ?>" class="btn btn-success btn-sm mx-1">လက်ခံသည်</a>
                                                    <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mx-1 reject-btn">ငြင်းပယ်သည်</a>
                                                    <!-- Complete button not shown -->
                                                <?php elseif ($row['status'] == 3): // Accepted 
                                                ?>
                                                    <a href="?action=complete&id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mx-1">ပြီးဆုံးသည်</a>
                                                    <!-- Accept/Reject not shown -->
                                                <?php elseif ($row['status'] == 1): // Completed 
                                                ?>
                                                    <span class="text-success">ပြီးဆုံးသည်</span>
                                                    <!-- No buttons shown -->
                                                <?php elseif ($row['status'] == 2): // Rejected 
                                                ?>
                                                    <span class="text-danger">ငြင်းပယ်သည်</span>
                                                    <!-- No buttons shown -->
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">ချိန်းဆိုမှုများမတွေ့ရှိပါ။</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>
<?php
require '../layouts/footer.php';
?>
<script>
    $(document).ready(function() {
        $('.reject-btn').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            Swal.fire({
                title: 'ငြင်းပယ်မည်ဆိုတာသေချာပြီလား',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ငြင်းပယ်သည်',
                cancelButtonText: 'မငြင်းပယ်ပါ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>