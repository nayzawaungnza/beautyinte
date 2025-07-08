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
$sql = "SELECT a.*, c.name AS customer_name, s.name AS service_name
        FROM appointments a
        INNER JOIN customers c ON a.customer_id = c.id
        INNER JOIN services s ON a.service_id = s.id
        WHERE a.staff_id = $staff_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC";
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
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($appointments && $appointments->num_rows > 0): ?>
                                    <?php while ($row = $appointments->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['appointment_date']) ?></td>
                                            <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 0) {
                                                    echo "<span class='badge bg-warning'>Pending</span>";
                                                } elseif ($row['status'] == 3) {
                                                    echo "<span class='badge bg-info'>Accepted</span>";
                                                } elseif ($row['status'] == 1) {
                                                    echo "<span class='badge bg-success'>Complete</span>";
                                                } elseif ($row['status'] == 2) {
                                                    echo "<span class='badge bg-danger'>Rejected</span>";
                                                } else {
                                                    echo "<span class='badge bg-secondary'>Unknown</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == 0): // Pending 
                                                ?>
                                                    <a href="?action=accept&id=<?= $row['id'] ?>" class="btn btn-success btn-sm mx-1">Accept</a>
                                                    <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mx-1 reject-btn">Reject</a>
                                                    <!-- Complete button not shown -->
                                                <?php elseif ($row['status'] == 3): // Accepted 
                                                ?>
                                                    <a href="?action=complete&id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mx-1">Complete</a>
                                                    <!-- Accept/Reject not shown -->
                                                <?php elseif ($row['status'] == 1): // Completed 
                                                ?>
                                                    <span class="text-success">Done</span>
                                                    <!-- No buttons shown -->
                                                <?php elseif ($row['status'] == 2): // Rejected 
                                                ?>
                                                    <span class="text-danger">Rejected</span>
                                                    <!-- No buttons shown -->
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No appointments found.</td>
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
                title: 'Are you sure?',
                text: "Do you really want to reject this appointment?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reject it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>