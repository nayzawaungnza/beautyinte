<?php

require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = "SELECT appointments.id, customers.name as customer_name, services.name AS `service_name`, users.name AS staff_name,
        appointments.appointment_date AS app_date, appointments.appointment_time AS app_time, appointments.status As status,appointments.comment, appointments.request
        FROM `appointments` Inner JOIN customers ON appointments.customer_id = customers.id
        INNER JOIN services ON appointments.service_id = services.id inner join users on appointments.staff_id = users.id
        ORDER BY appointments.id DESC";
$appointments = $mysqli->query($res);


$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('appointments', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "appointment_list.php?success=Delete Appointment Success";
        header("Location: $url");
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h1>အချိန်ချိန်းဆိုမှု စာရင်း</h1>
            <div class="">
                <a href="<?= $admin_base_url . 'appointment_create.php' ?>" class="btn btn-primary">
                    အချိန်ချိန်းဆိုမှု အသစ်ဖန်တီးရန်
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== '') { ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php } ?>
                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="">နံပါတ်</th>
                                    <th class="">ဖောက်သည်အမည်</th>
                                    <th class="">ဝန်ဆောင်မှု အမည်</th>
                                    <th class="">ဝန်ထမ်း အမည်</th>
                                    <th class="">ချိန်းဆိုသည့် ရက်စွဲ</th>
                                    <th class="">ချိန်းဆိုသည့် အချိန်</th>
                                    <th class="">အခြေအနေ</th>
                                    <th class="">မှတ်ချက်</th>
                                    <th class="">တောင်းဆိုမှု</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($appointments->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $appointments->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['customer_name'] ?></td>
                                            <td><?= $row['service_name'] ?></td>
                                            <td><?= $row['staff_name'] ?></td>
                                            <td><?= $row['app_date'] ?></td>
                                            <td><?= $row['app_time'] ?></td>
                                            <td><?php
                                                if ($row['status'] == 0) {
                                                    echo "<span class='badge bg-warning text-light'>Pending</span>";
                                                } elseif ($row['status'] == 1) {
                                                    echo "<span class='badge bg-success text-light'>Complete</span>";
                                                } else {
                                                    echo "<span class='badge bg-danger text-light'>Reject</span>";;
                                                }
                                                ?></td>
                                            <td><?= $row['comment'] ?></td>
                                            <td><?= $row['request'] ?></td>
                                            <td>
                                                <div>

                                                    <a href="./appointment_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2">ပြင်ဆင်ရန်</a>
                                                    <button data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn mx-2">ဖျက်ရန်</button>
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>
<!--**********************************
            Content body end
        ***********************************-->
<script>
    $(document).ready(function() {
        $('.delete_btn').click(function() {
            console.log('click');
            const id = $(this).data('id')
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'appointment_list.php?delete_id=' + id
                }
            });
        })
    })
</script>
<?php
require '../layouts/footer.php';
?>