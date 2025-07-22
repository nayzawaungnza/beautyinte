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
            echo "<script>alert('Appointment cancelled successfully!'); window.location.href='dashboard.php';</script>";
        } else {
            $error = "Failed to cancel appointment. Please try again.";
        }
    } else {
        $error = "Appointment not found, already processed, or you don't have permission to cancel it.";
    }
}

// Fetch customer's appointments
$sql = "SELECT a.*, s.name as service_name, s.price, u.name as staff_name 
        FROM appointments a
        JOIN services s ON a.service_id = s.id
        JOIN users u ON a.staff_id = u.id
        WHERE a.customer_id = '$customer_id'
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";

$result = $mysqli->query($sql);
?>

<div class="content-body">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">My Appointments</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if ($result && $result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Service</th>
                                    <th>Staff</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($appointment = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($appointment['service_name']) ?></td>
                                        <td><?= htmlspecialchars($appointment['staff_name']) ?></td>
                                        <td><?= date('M j, Y', strtotime($appointment['appointment_date'])) ?></td>
                                        <td><?= date('h:i A', strtotime($appointment['appointment_time'])) ?></td>
                                        <td>
                                            <?php
                                            // Updated status definitions according to requirements
                                            $status_badge = [
                                                0 => ['class' => 'badge bg-warning', 'text' => 'Pending'],
                                                1 => ['class' => 'badge bg-success', 'text' => 'Completed'],
                                                2 => ['class' => 'badge bg-danger', 'text' => 'Rejected'],
                                                3 => ['class' => 'badge bg-info', 'text' => 'Accepted']
                                            ];
                                            ?>
                                            <span class="<?= $status_badge[$appointment['status']]['class'] ?>">
                                                <?= $status_badge[$appointment['status']]['text'] ?>
                                            </span>
                                        </td>
                                        <td>

                                            <!-- Cancel Button (only for pending status = 0) -->
                                            <?php if ($appointment['status'] == 0): ?>
                                                <form method="POST" style="display: inline-block;">
                                                    <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                                                    <button type="submit" name="cancel_appointment" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                                        <i class="fas fa-times"></i> Cancel
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
                        You don't have any appointments yet. <a href="services.php">Book a service now!</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>