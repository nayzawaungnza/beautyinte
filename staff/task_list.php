<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';
require '../layouts/header.php';

// ====== today date =======
$today = date('Y-m-d'); // YYYY-MM-DD

// ====== Fetch only today appointments based on created_at =======
$sql = "SELECT a.id, c.name AS customer_name, s.name AS staff_name, 
               a.selected_service_names AS service_name,
               a.created_at AS app_date, a.appointment_time AS app_time, a.status
        FROM appointments a
        INNER JOIN users AS c ON c.id = a.customer_id
        INNER JOIN users AS s ON s.id = a.staff_id
        WHERE DATE(a.created_at) = '$today'
        ORDER BY a.appointment_time ASC";

$appointments = $mysqli->query($sql);
?>

<div class="content-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h3>ချိန်းဆိုမှုများ</h3>
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
                                            <td><?= htmlspecialchars($row['service_name']) ?></td>
                                            <td><?= htmlspecialchars($row['staff_name']) ?></td>
                                            <td><?= htmlspecialchars($row['app_date']) ?></td>
                                            <td><?= htmlspecialchars($row['app_time']) ?></td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 0) echo "<span class='badge bg-warning' style='font-size:15px; color:white;'>စောင့်နေသည်</span>";
                                                elseif ($row['status'] == 3) echo "<span class='badge bg-info' style='font-size:15px; color:white;'>လက်ခံသည်</span>";
                                                elseif ($row['status'] == 1) echo "<span class='badge bg-success' style='font-size:15px; color:white;'>ပြီးဆုံးသည်</span>";
                                                elseif ($row['status'] == 2) echo "<span class='badge bg-danger' style='font-size:15px; color:white;'>ငြင်းပယ်သည်</span>";
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['status'] == 0): ?>
                                                    <a href="tasks.php?action=accept&id=<?= $row['id'] ?>" class="btn btn-success btn-sm mx-1" style="font-size:15px;">လက်ခံသည်</a>
                                                    <a href="tasks.php?action=reject&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mx-1 reject-btn" style="font-size:15px;">ငြင်းပယ်သည်</a>
                                                <?php elseif ($row['status'] == 3): ?>
                                                    <a href="tasks.php?action=complete&id=<?= $row['id'] ?>" class="btn btn-primary btn-sm mx-1" style="font-size:15px;">ပြီးဆုံးသည်</a>
                                                <?php endif; ?>
                                            </td>
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

<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/sweetalert2.all.min.js"></script>
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
                if (result.isConfirmed) window.location.href = url;
            });
        });
    });
</script>