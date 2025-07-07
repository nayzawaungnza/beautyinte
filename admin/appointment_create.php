<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';
require '../layouts/header.php';
$error = false;
$customer_id_error = $service_id_error = $staff_id_error = $appointment_date_err = $appointment_time_err = $status_err = $request_err = '';
$customer_id = $service_id = $staff_id = $appointment_date = $appointment_time = $status = $comment = $request = '';
$general_error = '';
date_default_timezone_set('Asia/Yangon');

// Fetch customers for dropdown
$customers = $mysqli->query("SELECT id, name FROM customers ORDER BY name ASC");
// Fetch services for dropdown
$services = $mysqli->query("SELECT id, name FROM services ORDER BY name ASC");
// Fetch staff for dropdown
$users = $mysqli->query("SELECT id, name FROM users ORDER BY name ASC");

if (isset($_POST['btn_submit'])) {
    $customer_id = $_POST['customer_id'];
    $service_id = $_POST['service_id'];
    $staff_id = $_POST['staff_id'];
    $appointment_date = $_POST['app_date'];
    $appointment_time = $_POST['app_time'];
    $status = $_POST['status'];
    $comment = $_POST['comment'];
    $request = $_POST['request'];
    $today = date('Y-m-d');
    $current_time = date('H:i:s');

    if (empty($customer_id) || !is_numeric($customer_id)) {
        $error = true;
        $customer_id_error = "Please select a customer.";
    }
    if (empty($service_id) || !is_numeric($service_id)) {
        $error = true;
        $service_id_error = "Please select a service.";
    }
    if (empty($staff_id) || !is_numeric($staff_id)) {
        $error = true;
        $staff_id_error = "Please select a staff member.";
    }
    if (empty($appointment_date)) {
        $error = true;
        $appointment_date_err = "Please add appointment date.";
    } elseif ($appointment_date < $today) {
        $appointment_date_err = "Appointment date must not be in the past.";
        $error = true;
    }
    if (empty($appointment_time)) {
        $error = true;
        $appointment_time_err = "Please add appointment time.";
    } elseif ($appointment_date == $today && $appointment_time <= $current_time) {
        $error = true;
        $appointment_time_err = "Unavailable appointment time.";
    }
    if ($status === '' || !in_array($status, ['0', '1', '2'])) {
        $error = true;
        $status_err = "Please select status.";
    }

    if (!$error) {
        $customer_id = $mysqli->real_escape_string($customer_id);
        $service_id = $mysqli->real_escape_string($service_id);
        $staff_id = $mysqli->real_escape_string($staff_id);
        $appointment_date = $mysqli->real_escape_string($appointment_date);
        $appointment_time = $mysqli->real_escape_string($appointment_time);
        $status = $mysqli->real_escape_string($status);
        $comment = $mysqli->real_escape_string($comment);
        $request = $mysqli->real_escape_string($request);
        $sql = "INSERT INTO appointments (customer_id, service_id, staff_id, appointment_date, appointment_time, status, comment, request) VALUES ('$customer_id', '$service_id', '$staff_id', '$appointment_date', '$appointment_time', '$status', '$comment', '$request')";
        $mysqli->query($sql);
        echo "<script>window.location.href = '" . $admin_base_url . "appointment_list.php?success=Appointment Created';</script>";
        exit;
    } else {
        $general_error = "Please fix the errors below.";
    }
}
?>

<!-- Content body start -->

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">အလှပြင်ဆိုင် စနစ်အနှစ်ချုပ်မျက်နှာပြင်</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">ပင်မစာမျက်နှာ</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>အချိန်ချိန်းဆိုမှုစာရင်းဖန်တီးပါ</h3>
                <?php if ($error && $general_error) { ?>
                    <div class="alert alert-danger">
                        <?= $general_error ?>
                    </div>
                <?php } ?>
                <form method="POST">
                    <div class="form-group mb-2">
                        <label for="customer_id" class="form-label">ဖောက်သည်</label>
                        <select name="customer_id" class="form-control" id="customer_id">
                            <option value="">ဖောက်သည်ရွေးချယ်ရန်</option>
                            <?php if ($customers && $customers->num_rows > 0) {
                                while ($row = $customers->fetch_assoc()) {
                                    $selected = ($customer_id == $row['id']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                }
                            } else {
                                echo '<option value="">ဖောက်သည်မရှိပါ</option>';
                            } ?>
                        </select>
                        <?php if ($error && $customer_id_error) { ?>
                            <span class="text-danger"><?= $customer_id_error ?></span>
                        <?php } ?>
                    </div>
                    <div class="form-group mb-2">
                        <label for="service_id" class="form-label">ဝန်ဆောင်မှု</label>
                        <select name="service_id" class="form-control" id="service_id">
                            <option value="">ဝန်ဆောင်မှုရွေးချယ်ရန်</option>
                            <?php if ($services && $services->num_rows > 0) {
                                while ($row = $services->fetch_assoc()) {
                                    $selected = ($service_id == $row['id']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                }
                            } else {
                                echo '<option value="">ဝန်ဆောင်မှုမရှိပါ</option>';
                            } ?>
                        </select>
                        <?php if ($error && $service_id_error) { ?>
                            <span class="text-danger"><?= $service_id_error ?></span>
                        <?php } ?>
                    </div>
                    <div class="form-group mb-2">
                        <label for="staff_id" class="form-label">ဝန်ထမ်း</label>
                        <select name="staff_id" class="form-control" id="staff_id">
                            <option value="">ဝန်ထမ်းရွေးချယ်ရန်</option>
                            <?php if ($users && $users->num_rows > 0) {
                                while ($row = $users->fetch_assoc()) {
                                    $selected = ($staff_id == $row['id']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
                                }
                            } else {
                                echo '<option value="">ဝန်ထမ်းမရှိပါ</option>';
                            } ?>
                        </select>
                        <?php if ($error && $staff_id_error) { ?>
                            <span class="text-danger"><?= $staff_id_error ?></span>
                        <?php } ?>
                    </div>
                    <div class="form-group mb-2">
                        <label for="app_date" class="form-label">ချိန်းဆိုသည့် ရက်စွဲ</label>
                        <input type="date" name="app_date" class="form-control" value="<?= htmlspecialchars($appointment_date) ?>">
                        <?php if ($error && $appointment_date_err) { ?>
                            <small class="text-danger"><?= $appointment_date_err ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group mb-2">
                        <label for="app_time" class="form-label">ချိန်းဆိုသည့် အချိန်</label>
                        <input type="time" name="app_time" class="form-control" value="<?= htmlspecialchars($appointment_time) ?>">
                        <?php if ($error && $appointment_time_err) { ?>
                            <small class="text-danger"><?= $appointment_time_err ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group mb-2">
                        <label for="status" class="form-label">အခြေအနေ</label>
                        <select name="status" class="form-control" id="status">
                            <option value="">အခြေအနေရွေးချယ်ရန်</option>
                            <option value="0" <?= $status === '0' ? 'selected' : '' ?>>ဆိုင်းငံ့သည်</option>
                            <option value="1" <?= $status === '1' ? 'selected' : '' ?>>ပြီးဆုံးသည်</option>
                            <option value="2" <?= $status === '2' ? 'selected' : '' ?>>ငြင်းပယ်သည်</option>
                            <option value="3" <?= $status === '3' ? 'selected' : '' ?>>လက်ခံသည်</option>
                        </select>
                        <?php if ($error && $status_err) { ?>
                            <small class="text-danger"><?= $status_err ?></small>
                        <?php } ?>
                    </div>
                    <div class="form-group mb-2">
                        <label for="comment" class="form-label">မှတ်ချက်</label>
                        <input type="text" name="comment" class="form-control" value="<?= htmlspecialchars($comment) ?>">
                    </div>
                    <div class="form-group mb-2">
                        <label for="request" class="form-label">တောင်းဆိုမှု</label>
                        <input type="text" name="request" class="form-control" value="<?= htmlspecialchars($request) ?>">
                        <?php if ($error && $request_err) { ?>
                            <small class="text-danger"><?= $request_err ?></small>
                        <?php } ?>
                    </div>
                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>

<!-- Content body end -->



<?php

require '../layouts/footer.php';

?>