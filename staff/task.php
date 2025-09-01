<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';
require '../layouts/header.php';

// Handle status update actions
if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'accept') {
        $mysqli->query("UPDATE appointments SET status=3 WHERE id=$id");
    } elseif ($action === 'reject') {
        $mysqli->query("UPDATE appointments SET status=2 WHERE id=$id");
    } elseif ($action === 'complete') {
        $mysqli->query("UPDATE appointments SET status=1 WHERE id=$id");
    }

    // JS redirect to task.php
    echo "<script>window.location.href='task_list.php';</script>";
    exit;
}

// Fetch all pending appointments (status = 0)
$stmt = $mysqli->prepare("
    SELECT a.id, a.customer_id, c.name AS customer_name, s.name AS staff_name,
           DATE(a.appointment_date) AS app_date, a.appointment_time AS app_time, a.status,
           a.request, a.selected_service_names
    FROM appointments a
    INNER JOIN users AS c ON c.id = a.customer_id
    INNER JOIN users AS s ON s.id = a.staff_id
    WHERE a.status = 0
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");

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
                                    <th>ဝန်ထမ်း</th>
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
                                            <td><?= htmlspecialchars($row['staff_name']) ?></td>
                                            <td><?= htmlspecialchars($row['app_date']) ?></td>
                                            <td><?= htmlspecialchars($row['app_time']) ?></td>
                                            <td>
                                                <span class='btn btn-sm bg-warning' style='color:white;'>စောင့်နေသည်</span>
                                            </td>
                                            <td>
                                                <a href="?action=accept&id=<?= $row['id'] ?>"
                                                    class="btn btn-success btn-sm">လက်ခံသည်</a>

                                                <a href="?action=reject&id=<?= $row['id'] ?>"
                                                    class="btn btn-danger btn-sm reject-btn">ငြင်းပယ်သည်</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">စောင့်နေသော ချိန်းဆိုမှုများ မရှိပါ။</td>
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