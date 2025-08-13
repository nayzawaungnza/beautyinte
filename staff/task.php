<?php
require '../require/check_auth.php';
checkAuth('staff');
require '../require/db.php';
require '../require/common.php';
require '../layouts/header.php';

// Assume staff is logged in and their user id is in session
$staff_id = $_SESSION['user_id'] ?? 0;
$customer_id = $_POST['customer_id'] ?? ''; // or $_GET if sent by URL


// အောက်က if statement က form submit ဖြစ်တဲ့အချိန်မှာသာ ထည့်သွင်းဖို့
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($customer_id)) {
    // Prepared statement ဖြင့် SQL injection ကာကွယ်
    $stmt = $mysqli->prepare("INSERT INTO appointments (customer_id, appointment_date, appointment_time, selected_service_names, status) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("iisss", $customer_id, $appointment_date, $appointment_time, $selected_service_names);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Appointment saved successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

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
    echo "<script>window.location.href='task.php';</script>";
    exit;
}

// Fetch appointments assigned to this staff member
$stmt = $mysqli->prepare("SELECT a.id, c.name AS customer_name, s.name AS staff_name,
a.appointment_date AS app_date, a.appointment_time AS app_time, a.status,
a.request, a.selected_service_names
FROM appointments a
INNER JOIN users AS c ON c.id = a.customer_id
INNER JOIN users AS s ON s.id = a.staff_id
WHERE s.id = ?
ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$appointments = $stmt->get_result();

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
                                            <td><?= htmlspecialchars($row['selected_service_names']) ?></td>
                                            <td><?= htmlspecialchars($row['app_date']) ?></td>
                                            <td><?= htmlspecialchars($row['app_time']) ?></td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 0) {
                                                    echo "<span class='btn btn-sm bg-warning' style='font-size:16px; font-weight:bold; color:white;'>စောင့်နေသည်</span>";
                                                } elseif ($row['status'] == 3) {
                                                    echo "<span class='btn btn-sm bg-info' style='font-size:16px; font-weight:bold; color:white;'>လက်ခံသည်</span>";
                                                } elseif ($row['status'] == 1) {
                                                    echo "<span class='btn btn-sm bg-success' style='font-size:16px; font-weight:bold; color:white;'>ပြီးဆုံးသည်</span>";
                                                } elseif ($row['status'] == 2) {
                                                    echo "<span class='btn btn-sm bg-danger' style='font-size:16px; font-weight:bold; color:white;'>ငြင်းပယ်သည်</span>";
                                                } else {
                                                    echo "<span class='btn btn-sm bg-secondary' style='font-size:16px; font-weight:bold; color:white;'>Unknown</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == 0): // Pending 
                                                ?>
                                                    <a href="?action=accept&id=<?= $row['id'] ?>" class="btn btn-success btn-sm mx-1" style="font-weight:bold; font-size:16px;">လက်ခံသည်</a>
                                                    <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mx-1 reject-btn" style="font-weight:bold; font-size:16px;">ငြင်းပယ်သည်</a>
                                                    <!-- Complete button not shown -->
                                                <?php elseif ($row['status'] == 3): // Accepted 
                                                ?>
                                                    <a href="?action=complete&id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mx-1" style="font-weight:bold; font-size:16px;">ပြီးဆုံးသည်</a>
                                                    <!-- Accept/Reject not shown -->
                                                <?php elseif ($row['status'] == 1): // Completed 
                                                ?>
                                                    <span class="text-success" style="font-size:16px; font-weight:bold;">ပြီးဆုံးသည်</span>
                                                    <!-- No buttons shown -->
                                                <?php elseif ($row['status'] == 2): // Rejected 
                                                ?>
                                                    <span class="text-danger" style="font-size:16px; font-weight:bold;">ငြင်းပယ်သည်</span>
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