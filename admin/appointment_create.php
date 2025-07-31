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

$customers = $mysqli->query("SELECT id, name FROM users WHERE id = '$customerIdUrl'");
$services = $mysqli->query("SELECT id, name, price, description FROM services ORDER BY name ASC");
$users = $mysqli->query("SELECT id, name FROM users WHERE role = 'staff' ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'] ?? '';
    $service_id = $_POST['service_id'] ?? '';
    $staff_id = $_POST['staff_id'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $comment = $_POST['comment'] ?? '';
    $request = $_POST['request'] ?? '';

    $today = date('Y-m-d');
    $current_time = date('H:i:s');

    if (empty($service_id)) {
        $error = true;
        $service_id_error = "ကျေးဇူးပြုပြီး ဝန်ဆောင်မှုကို ရွေးချယ်ပါ။";
    }
    if (empty($staff_id)) {
        $error = true;
        $staff_id_error = "ကျေးဇူးပြုပြီး ဝန်ထမ်းတစ်ဦးကို ရွေးချယ်ပါ။";
    }
    if (empty($appointment_date)) {
        $error = true;
        $appointment_date_err = "ကျေးဇူးပြုပြီး ချိန်းဆိုမည့်ရက်ကို ထည့်သွင်းပါ။";
    } elseif (strtotime($appointment_date) < strtotime($today)) {
        $error = true;
        $appointment_date_err = "ရွေးချယ်ထားသောရက်သည် သက်တမ်းကုန်သွားပြီးပါပြီ။";
    }
    if (empty($appointment_time)) {
        $error = true;
        $appointment_time_err = "ကျေးဇူးပြုပြီး ချိန်းဆိုချိန်ကို ထည့်သွင်းပါ။";
    } else {
        $time = strtotime($appointment_time);
        if ($time < strtotime('09:00') || $time > strtotime('21:00')) {
            $error = true;
            $appointment_time_err = "ချိန်းဆိုချိန်သည် မနက် ၉:၀၀ မှ ည ၉:၀၀ အတွင်း ဖြစ်ရမည်။";
        } elseif ($time >= strtotime('12:00') && $time < strtotime('13:00')) {
            $error = true;
            $appointment_time_err = "ချိန်းဆိုချိန်သည် နေ့လည် ၁၂:၀၀ မှ ၁:၀၀ အတွင်း မဖြစ်နိုင်ပါ။";
        } elseif ($appointment_date == $today && $appointment_time <= $current_time) {
            $error = true;
            $appointment_time_err = "ချိန်းဆိုချိန် မရနိုင်ပါ။";
        }
    }

    if (!$error) {
        $conflict_sql = "SELECT id FROM appointments WHERE staff_id = '$staff_id' AND appointment_date = '$appointment_date' AND appointment_time = '$appointment_time' AND (status = 0 OR status = 3)";
        $conflict_result = $mysqli->query($conflict_sql);

        if ($conflict_result && $conflict_result->num_rows > 0) {
            $error = true;
            $staff_id_error = "ဤဝန်ထမ်းသည် လုပ်ဆောင်ရန်အလုပ်ရှိပြီးဖြစ်ပါသည်။";
        }
    }

    if (!$error) {
        $sql = "INSERT INTO appointments (customer_id, service_id, staff_id, appointment_date, appointment_time, status, comment, request) VALUES ('$customerIdUrl', '$service_id', '$staff_id', '$appointment_date', '$appointment_time', '0', '$comment', '$request')";
        if ($mysqli->query($sql)) {
            echo "<script> window.location.href = 'appointment_list.php';</script>";
            exit();
        } else {
            $general_error = 'ချိန်းဆိုမှု မအောင်မြင်ပါ။';
        }
    }
}
?>

<div class="content-body">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h3 class="mb-0 text-center">Admin - ချိန်းဆိုရန်</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($general_error): ?>
                            <div class="alert alert-danger text-center"><?= $general_error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-4">
                                <label for="service_id" class="form-label fw-bold">ဝန်ဆောင်မှု</label>
                                <select name="service_id" class="form-select <?= $service_id_error ? 'is-invalid' : '' ?>">
                                    <option value="" selected disabled>ဝန်ဆောင်မှုရွေးချယ်ရန်</option>
                                    <?php while ($s = $services->fetch_assoc()): ?>
                                        <option value="<?= $s['id'] ?>" <?= ($service_id == $s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?> (<?= number_format($s['price'], 0) ?> Ks)</option>
                                    <?php endwhile; ?>
                                </select>
                                <?php if ($service_id_error): ?><div class="invalid-feedback"><?= $service_id_error ?></div><?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="staff_id" class="form-label fw-bold">ဝန်ထမ်း</label>
                                <select name="staff_id" class="form-select <?= $staff_id_error ? 'is-invalid' : '' ?>">
                                    <option value="" selected disabled>ဝန်ထမ်းရွေးချယ်ရန်</option>
                                    <?php while ($u = $users->fetch_assoc()): ?>
                                        <option value="<?= $u['id'] ?>" <?= ($staff_id == $u['id']) ? 'selected' : '' ?>><?= htmlspecialchars($u['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <?php if ($staff_id_error): ?><div class="invalid-feedback"><?= $staff_id_error ?></div><?php endif; ?>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="appointment_date" class="form-label fw-bold">ချိန်းဆိုရက်</label>
                                    <input type="date" name="appointment_date" class="form-control <?= $appointment_date_err ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($appointment_date) ?>">
                                    <?php if ($appointment_date_err): ?><div class="invalid-feedback"><?= $appointment_date_err ?></div><?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_time" class="form-label fw-bold">ချိန်းဆိုချိန်</label>
                                    <input type="time" name="appointment_time" class="form-control <?= $appointment_time_err ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($appointment_time) ?>">
                                    <?php if ($appointment_time_err): ?><div class="invalid-feedback"><?= $appointment_time_err ?></div><?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="form-label fw-bold">မှတ်စု (လိုအပ်ပါက)</label>
                                <textarea name="comment" class="form-control" rows="2"><?= htmlspecialchars($comment) ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="request" class="form-label fw-bold">အထူးတောင်းဆိုချက်(လိုအပ်ပါက)</label>
                                <textarea name="request" class="form-control" rows="2"><?= htmlspecialchars($request) ?></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg py-3">ချိန်းဆိုမှု တင်သွင်းရန်</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-muted">အကူအညီလိုပါသလား? <a href="#" class="text-primary">ပံ့ပိုးမှုအဖွဲ့ကို ဆက်သွယ်ပါ</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>