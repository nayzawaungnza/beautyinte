<?php
require '../require/check_auth.php';
checkAuth('staff');
require '../require/db.php';
require '../require/common.php';
require '../layouts/header.php';

// ====== today date =======
$today = date('Y-m-d'); // YYYY-MM-DD

// ====== Get current staff ID from session ======
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'staff') {
    die("Unauthorized access.");
}
$staffId = $_SESSION['id'];   // ✅ Login ဝင်လာတဲ့ staff ID

// ====== Fetch only today appointments for this staff =======
$sql = "SELECT a.id, c.name AS customer_name, s.name AS staff_name, 
               a.selected_service_names AS service_name,
               a.created_at AS app_date, a.appointment_time AS app_time, a.status
        FROM appointments a
        INNER JOIN users AS c ON c.id = a.customer_id
        INNER JOIN users AS s ON s.id = a.staff_id
        WHERE DATE(a.created_at) = ?
          AND a.staff_id = ?
        ORDER BY a.appointment_time ASC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $today, $staffId);
$stmt->execute();
$appointments = $stmt->get_result();
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h3>ယနေ့ ချိန်းဆိုမှုများ (<?= htmlspecialchars($_SESSION['name']) ?>)</h3>
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>ဖောက်သည်</th>
                                    <th>ဝန်ဆောင်မှု</th>
                                    <th>ဝန်ထမ်း</th>
                                    <th>ရက်စွဲ</th>
                                    <th>အချိန်</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($appointments && $appointments->num_rows > 0): ?>
                                    <?php while ($row = $appointments->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['staff_name']) ?></td>
                                            <td><?= htmlspecialchars($row['app_date']) ?></td>
                                            <td><?= htmlspecialchars($row['app_time']) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">ယနေ့အတွက် ချိန်းဆိုမှု မရှိပါ။</td>
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