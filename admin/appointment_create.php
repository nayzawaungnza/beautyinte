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

$customerIdUrl = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch customers for dropdown
$customers = $mysqli->query("SELECT id, name FROM customers WHERE id = '$customerIdUrl'");
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
    // $status = $_POST['status'];
    $comment = $_POST['comment'];
    $request = $_POST['request'];
    $today = date('Y-m-d');
    $current_time = date('H:i:s');

    // if (empty($customer_id) || !is_numeric($customer_id)) {
    //     $error = true;
    //     $customer_id_error = "ကျေးဇူးပြုပြီး ဖောက်သည်ကို ရွေးချယ်ပါ။";
    // }
    if (empty($service_id) || !is_numeric($service_id)) {
        $error = true;
        $service_id_error = "ကျေးဇူးပြုပြီး ဝန်ဆောင်မှုကို ရွေးချယ်ပါ။";
    }
    if (empty($staff_id) || !is_numeric($staff_id)) {
        $error = true;
        $staff_id_error = "ကျေးဇူးပြုပြီး ဝန်ထမ်းတစ်ဦးကို ရွေးချယ်ပါ။";
    }
    if (empty($appointment_date)) {
        $error = true;
        $appointment_date_err = "ကျေးဇူးပြုပြီး ချိန်းဆိုမည့်ရက်ကို ထည့်သွင်းပါ။";
    } elseif (strtotime($appointment_date) < strtotime($today)) {
        $error = true;
        $appointment_date_err = "ချိန်းဆိုရက်သည် အတိတ်အချိန်မဖြစ်ရပါ။";
    }
    if (empty($appointment_time)) {
        $error = true;
        $appointment_time_err = "ကျေးဇူးပြုပြီး ချိန်းဆိုချိန်ကို ထည့်သွင်းပါ။";
    } else {
        // Convert to seconds for easy comparison
        $time = strtotime($appointment_time);
        $start = strtotime('09:00');
        $end = strtotime('21:00');
        $lunch_start = strtotime('12:00');
        $lunch_end = strtotime('13:00');

        if ($time < $start || $time > $end) {
            $error = true;
            $appointment_time_err = "ချိန်းဆိုချိန်သည် မနက် ၉:၀၀ မှ ည ၉:၀၀ အတွင်း ဖြစ်ရမည်။";
        } elseif ($time >= $lunch_start && $time < $lunch_end) {
            $error = true;
            $appointment_time_err = "ချိန်းဆိုချိန်သည် နေ့လည် ၁၂:၀၀ မှ ၁:၀၀ အတွင်း မဖြစ်နိုင်ပါ။";
        } elseif ($appointment_date == $today && $appointment_time <= $current_time) {
            $error = true;
            $appointment_time_err = "ချိန်းဆိုချိန် မရနိုင်ပါ။";
        }
    }

    // Check if staff is already assigned at the same date and time
    if (!$error) {
        $staff_id_esc = $mysqli->real_escape_string($staff_id);
        $appointment_date_esc = $mysqli->real_escape_string($appointment_date);
        $appointment_time_esc = $mysqli->real_escape_string($appointment_time);

        // Check for same date and time
        $conflict_sql = "SELECT id FROM appointments 
                         WHERE staff_id = '$staff_id_esc' AND appointment_date = '$appointment_date' 
                         AND status = '0' OR status = '3' ";
        $conflict_result = $mysqli->query($conflict_sql);

        if ($conflict_result && $conflict_result->num_rows > 0) {
            $error = true;
            $staff_id_error = "ဤဝန်ထမ်းသည် လုပ်ဆောင်ရန်အလုပ်ရှိပြီးဖြစ်ပါသည်။";
        }
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
        $sql = "INSERT INTO appointments (customer_id, service_id, staff_id, appointment_date, appointment_time, status, comment, request) VALUES ('$customer_id', '$service_id', '$staff_id', '$appointment_date', '$appointment_time', '0', '$comment', '$request')";
        $mysqli->query($sql);
        echo "<script>window.location.href = '" . $admin_base_url . "appointment_list.php?success=Appointment Created';</script>";
        exit;
    }
    //  else {
    // //     $general_error = "ကျေးဇူးပြုပြီး အောက်ပါအမှားများကို ပြင်ဆင်ပါ။";
    // // }
}
?>

<!-- Content body start -->

<div class="content-body">


    <!-- row -->

    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">အချိန်ချိန်းဆိုမှုစာရင်းဖန်တီးပါ</h3>
                <?php if ($error && $general_error) { ?>
                    <div class="alert alert-danger">
                        <?= $general_error ?>
                    </div>
                <?php } ?>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="customer_id" class="form-label">ဖောက်သည်</label>
                                <select name="customer_id" class="form-control" id="customer_id">
                                    <?php if ($customers && $customers->num_rows > 0) {
                                        $c = $customers->fetch_assoc(); ?>
                                        <option value="<?= $c['id'] ?>" selected><?= $c['name'] ?> </option>
                                    <?php }  ?>
                                </select>
                                <?php if ($error && $customer_id_error) { ?>
                                    <span class="text-danger"><?= $customer_id_error ?></span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="app_date" class="form-label">ချိန်းဆိုသည့် ရက်စွဲ</label>
                                <input type="date" name="app_date" class="form-control" value="<?= htmlspecialchars($appointment_date) ?>">
                                <?php if ($error && $appointment_date_err) { ?>
                                    <small class="text-danger"><?= $appointment_date_err ?></small>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="app_time" class="form-label">ချိန်းဆိုသည့် အချိန်</label>
                                <input type="time" name="app_time" class="form-control" value="<?= htmlspecialchars($appointment_time) ?>">
                                <?php if ($error && $appointment_time_err) { ?>
                                    <small class="text-danger"><?= $appointment_time_err ?></small>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- <div class="form-group mb-2">
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
                    </div> -->
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="comment" class="form-label">မှတ်ချက်</label>
                                <input type="text" name="comment" class="form-control" value="<?= htmlspecialchars($comment) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        </div>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- #/ container -->
<!-- Content body end -->


<?php
require '../layouts/footer.php';
?>