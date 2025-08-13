<?php
require '../require/check_auth.php';
checkAuth('customer');
require '../layouts/header.php';

$customer_id = $_SESSION['id'];

// Handle appointment cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment'])) {
    $appointment_id = intval($_POST['appointment_id']);

    // Verify the appointment belongs to the customer and is pending
    $check_sql = "SELECT id FROM appointments 
                 WHERE id = '$appointment_id' 
                 AND customer_id = '$customer_id'
                 AND status = 0"; // Only allow cancellation if status is 0 (pending)

    $check_result = $mysqli->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        $update_sql = "UPDATE appointments SET status = 2 WHERE id = '$appointment_id'"; // Set to rejected (2) when cancelled
        if ($mysqli->query($update_sql)) {
            echo "<script>alert('ချိန်းဆိုမှုကိုအောင်မြင်စွာဖျက်လိုက်ပါပြီ!'); window.location.href='dash.php';</script>";
        } else {
            $error = "Failed to cancel appointment. Please try again.";
        }
    } else {
        $error = "Appointment not found, already processed, or you don't have permission to cancel it.";
    }
}

// Fetch customer's appointments
$sql = "SELECT a.*, u.name as staff_name 
        FROM appointments a
        JOIN users u ON a.staff_id = u.id
        WHERE a.customer_id = '$customer_id'
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$result = $mysqli->query($sql);
?>

<div class="content-body">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">ချိန်းဆိုမှုများ</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>ဝန်ဆောင်မှု</th>
                                    <th>ဝန်ထမ်း</th>
                                    <th>ရက်စွဲ</th>
                                    <th>အချိန်</th>
                                    <th>အခြေအနေ</th>
                                    <th>လုပ်ဆောင်မှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($appointment = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td style="color:black"><?= htmlspecialchars($appointment['selected_service_names']) ?></td>
                                        <td style="color:black"><?= htmlspecialchars($appointment['staff_name']) ?></td>
                                        <td style="color:black"><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                        <td style="color:black"><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                                        <td><?php
                                            if ($appointment['status'] == 0) {
                                                echo "<span class='btn btn-sm bg-warning text-white' style='font-size:16px; font-weight:bold;'>စောင့်နေသည်</span>";
                                            } elseif ($appointment['status'] == 1) {
                                                echo "<span class='btn btn-sm bg-success text-white' style='font-size:16px; font-weight:bold;'>ပြီးဆုံးသည်</span>";
                                            } elseif ($appointment['status'] == 3) {
                                                echo "<span class='btn btn-sm bg-primary text-white' style='font-size:16px; font-weight:bold;'>လက်ခံသည်</span>";
                                            } else {
                                                echo "<span class='btn btn-sm bg-danger text-white' style='font-size:16px; font-weight:bold;'>ငြင်းပယ်သည်</span>";
                                            }
                                            ?>
                                        </td>

                                        <td>

                                            <!-- Cancel Button (only for pending status = 0) -->
                                            <?php if ($appointment['status'] == 0): ?>
                                                <form method="POST" style="display: inline-block;">
                                                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                    <button type="submit" name="cancel_appointment" style="color:white; background-color:crimson;" class="btn btn-sm"
                                                        onclick="return confirm('ချိန်းဆိုမှုကိုပယ်ဖျက်ရန်သေချာပြီလား')">
                                                        <i class="fas fa-times"></i> ပယ်ဖျက်ပါ
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        အခုအချိန်ထိ ချိန်းဆိုမှု မရှိသေးပါ <a href="services.php">ဝန်ဆောင်မှုကို ယခုဘဲ ချိန်းလိုက်ပါ!</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>