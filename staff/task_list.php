<?php
require '../require/check_auth.php';
checkAuth('staff');
require '../require/db.php';
require '../require/common.php';
require '../layouts/header.php';

// Assume staff is logged in and their user id is in session
$staff_id = $_SESSION['user_id'] ?? 0;

// Handle status update actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    if ($action === 'accept') {
        $mysqli->query("UPDATE appointments SET status=1 WHERE id=$id AND staff_id=$staff_id");
    } elseif ($action === 'reject') {
        $mysqli->query("UPDATE appointments SET status=2 WHERE id=$id AND staff_id=$staff_id");
    } elseif ($action === 'complete') {
        $mysqli->query("UPDATE appointments SET status=1 WHERE id=$id AND staff_id=$staff_id");
    }
    echo "<script>window.location.href='task_list.php';</script>";
    exit;
}

// Fetch appointments for this staff
$sql = "SELECT a.*, c.name as customer_name, s.name as service_name
        FROM appointments a
        INNER JOIN customers c ON a.customer_id = c.id
        INNER JOIN services s ON a.service_id = s.id
        WHERE a.staff_id = $staff_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$appointments = $mysqli->query($sql);
?>

<div class="container">
    <h3>My Appointments</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($appointments && $appointments->num_rows > 0) {
                while ($row = $appointments->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['customer_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['service_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['appointment_time']) . "</td>";
                    // Status
                    if ($row['status'] == 0) {
                        echo "<td><span class='badge bg-warning'>Pending</span></td>";
                    } elseif ($row['status'] == 1) {
                        echo "<td><span class='badge bg-success'>Complete</span></td>";
                    } elseif ($row['status'] == 2) {
                        echo "<td><span class='badge bg-danger'>Rejected</span></td>";
                    } else {
                        echo "<td><span class='badge bg-secondary'>Unknown</span></td>";
                    }
                    // Actions
                    echo "<td>";
                    if ($row['status'] == 0) {
                        echo "<a href='?action=accept&id={$row['id']}' class='btn btn-success btn-sm mx-1'>Accept</a>";
                        echo "<a href='?action=reject&id={$row['id']}' class='btn btn-danger btn-sm mx-1'>Reject</a>";
                    } elseif ($row['status'] == 1) {
                        echo "<span class='text-success'>Done</span>";
                    } elseif ($row['status'] == 2) {
                        echo "<span class='text-danger'>Rejected</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No appointments found.</td></tr>";
            } ?>
        </tbody>
    </table>
</div>

<?php
require '../layouts/footer.php';
?>