<?php
require '../require/check_auth.php';
checkAuth('customer');
require '../layouts/header.php';


$customer_id = $_SESSION['id'];
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;

// Fetch service info
$service = null;
if ($service_id) {
    $service_res = $mysqli->query("SELECT id, name, price, description FROM services WHERE id = '$service_id'");
    $service = $service_res ? $service_res->fetch_assoc() : null;
}

// Fetch staff for dropdown
$staff_res = $mysqli->query("SELECT id, name FROM users WHERE role = 'staff' AND id NOT IN (SELECT staff_id FROM appointments WHERE status NOT IN (0, 3)) ORDER BY name ASC");

// Form variables
$staff_id = $appointment_date = $appointment_time = $comment = $request = '';
$staff_id_err = $appointment_date_err = $appointment_time_err = $general_err = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : '';
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $appointment_time = trim($_POST['appointment_time'] ?? '');
    $comment = trim($_POST['comment'] ?? '');
    $request = trim($_POST['request'] ?? '');
    $today = date('Y-m-d');
    $current_time = date('H:i:s');

    if (!$service_id) {
        $general_err = 'ဝန်ဆောင်မှု မရှိပါ။';
        $error = true;
    }
    if (empty($staff_id) || !is_numeric($staff_id)) {
        $staff_id_err = 'ကျေးဇူးပြုပြီး ဝန်ထမ်းတစ်ဦးကို ရွေးချယ်ပါ။';
        $error = true;
    }
    if (empty($appointment_date)) {
        $appointment_date_err = 'ကျေးဇူးပြုပြီး ချိန်းဆိုမည့်ရက်ကို ထည့်သွင်းပါ။';
        $error = true;
    } elseif (strtotime($appointment_date) < strtotime($today)) {
        $appointment_date_err = 'ချိန်းဆိုရက်သည် အတိတ်အချိန်မဖြစ်ရပါ။';
        $error = true;
    }
    if (empty($appointment_time)) {
        $appointment_time_err = 'ကျေးဇူးပြုပြီး ချိန်းဆိုချိန်ကို ထည့်သွင်းပါ။';
        $error = true;
    } else {
        $time = strtotime($appointment_time);
        $start = strtotime('09:00');
        $end = strtotime('21:00');
        $lunch_start = strtotime('12:00');
        $lunch_end = strtotime('13:00');
        if ($time < $start || $time > $end) {
            $appointment_time_err = 'ချိန်းဆိုချိန်သည် မနက် ၉:၀၀ မှ ည ၉:၀၀ အတွင်း ဖြစ်ရမည်။';
            $error = true;
        } elseif ($time >= $lunch_start && $time < $lunch_end) {
            $appointment_time_err = 'ချိန်းဆိုချိန်သည် နေ့လည် ၁၂:၀၀ မှ ၁:၀၀ အတွင်း မဖြစ်နိုင်ပါ။';
            $error = true;
        } elseif ($appointment_date == $today && $appointment_time <= $current_time) {
            $appointment_time_err = 'ချိန်းဆိုချိန် မရနိုင်ပါ။';
            $error = true;
        }
    }
    // Check if staff is already assigned at the same date and time
    if (!$error) {
        $conflict_sql = "SELECT id FROM appointments WHERE staff_id = '$staff_id' AND appointment_date = '$appointment_date' AND appointment_time = '$appointment_time' AND (status = 0 OR status = 3)";
        $conflict_result = $mysqli->query($conflict_sql);
        if ($conflict_result && $conflict_result->num_rows > 0) {
            $staff_id_err = 'ဤဝန်ထမ်းသည် လုပ်ဆောင်ရန်အလုပ်ရှိပြီးဖြစ်ပါသည်။';
            $error = true;
        }
    }
    if (!$error) {
        $customer_id_esc = $mysqli->real_escape_string($customer_id);
        $service_id_esc = $mysqli->real_escape_string($service_id);
        $staff_id_esc = $mysqli->real_escape_string($staff_id);
        $appointment_date_esc = $mysqli->real_escape_string($appointment_date);
        $appointment_time_esc = $mysqli->real_escape_string($appointment_time);
        $comment_esc = $mysqli->real_escape_string($comment);
        $request_esc = $mysqli->real_escape_string($request);
        $sql = "INSERT INTO appointments (customer_id, service_id, staff_id, appointment_date, appointment_time, status, comment, request) VALUES ('$customer_id_esc', '$service_id_esc', '$staff_id_esc', '$appointment_date_esc', '$appointment_time_esc', 0, '$comment_esc', '$request_esc')";
        if ($mysqli->query($sql)) {
            echo "<script>alert('ချိန်းဆိုမှု အောင်မြင်စွာ တင်သွင်းပြီးပါပြီ!'); window.location.href = '../home.php';</script>";
            exit();
        } else {
            $general_err = 'ချိန်းဆိုမှု မအောင်မြင်ပါ။';
        }
    }
}
?>

<!-- <section class="ftco-section"> -->
<div class="container-fluid mt-3">
    <div class="row justify-content-center pb-3">
        <div class="col-md-8 heading-section ftco-animate text-center">
            <h2 class="mb-4">ဝန်ဆောင်မှုအတွက် ချိန်းဆိုရန်</h2>
            <?php if ($service): ?>
                <h4 class="mb-2">ဝန်ဆောင်မှု: <?= htmlspecialchars($service['name']) ?> (<?= htmlspecialchars($service['price']) ?> Ks)</h4>
            <?php else: ?>
                <div class="alert alert-danger">ဝန်ဆောင်မှု မရှိပါ။</div>
            <?php endif; ?>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-7">
            <?php if ($general_err): ?>
                <div class="alert alert-danger"><?= $general_err ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group mb-3">
                    <label for="staff_id">ဝန်ထမ်းရွေးချယ်ရန်</label>
                    <select name="staff_id" id="staff_id" class="form-control">
                        <option value="">ဝန်ထမ်းရွေးချယ်ရန်</option>
                        <?php if ($staff_res && $staff_res->num_rows > 0):
                            while ($row = $staff_res->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>" <?= ($staff_id == $row['id']) ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
                        <?php endwhile;
                        endif; ?>
                    </select>
                    <?php if ($staff_id_err): ?><small class="text-danger"><?= $staff_id_err ?></small><?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="appointment_date">ချိန်းဆိုသည့် ရက်စွဲ</label>
                    <input type="date" name="appointment_date" id="appointment_date" class="form-control" value="<?= htmlspecialchars($appointment_date) ?>">
                    <?php if ($appointment_date_err): ?><small class="text-danger"><?= $appointment_date_err ?></small><?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="appointment_time">ချိန်းဆိုသည့် အချိန်</label>
                    <input type="time" name="appointment_time" id="appointment_time" class="form-control" value="<?= htmlspecialchars($appointment_time) ?>">
                    <?php if ($appointment_time_err): ?><small class="text-danger"><?= $appointment_time_err ?></small><?php endif; ?>
                </div>
                <div class="form-group mb-3">
                    <label for="comment">မှတ်ချက်</label>
                    <input type="text" name="comment" id="comment" class="form-control" value="<?= htmlspecialchars($comment) ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="request">တောင်းဆိုချက်</label>
                    <input type="text" name="request" id="request" class="form-control" value="<?= htmlspecialchars($request) ?>">
                </div>
                <button type="submit" class="btn btn-primary w-100">ချိန်းဆိုရန် တင်သွင်းပါ</button>
            </form>
        </div>
    </div>
</div>
<!-- </section> -->
<?php require '../layouts/footer.php'; ?>