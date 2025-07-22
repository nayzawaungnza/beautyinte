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
$staff_sql = "SELECT users.name, users.id
FROM users
LEFT JOIN appointments ON appointments.staff_id = users.id 
  AND DATE(appointments.appointment_date) = CURDATE() 
  AND (appointments.status = 0 OR appointments.status = 1)
WHERE users.role = 'staff'
";
$staff_res = $mysqli->query($staff_sql);
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
            echo "<script>alert('ချိန်းဆိုမှု အောင်မြင်စွာ တင်သွင်းပြီးပါပြီ!'); window.location.href = './dashboard.php';</script>";
            exit();
        } else {
            $general_err = 'ချိန်းဆိုမှု မအောင်မြင်ပါ။';
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
                        <h3 class="mb-0 text-center">Book Your Appointment</h3>
                    </div>

                    <div class="card-body p-4">
                        <?php if ($service): ?>
                            <div class="service-summary mb-4 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 text-dark"><?= htmlspecialchars($service['name']) ?></h4>
                                    <span class="badge bg-success fs-6"><?= number_format($service['price'], 0) ?> Ks</span>
                                </div>
                                <?php if (!empty($service['description'])): ?>
                                    <p class="mt-2 mb-0 text-muted"><?= htmlspecialchars($service['description']) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger text-center">Service not found</div>
                        <?php endif; ?>

                        <?php if ($general_err): ?>
                            <div class="alert alert-danger"><?= $general_err ?></div>
                        <?php endif; ?>

                        <form method="POST" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="staff_id" class="form-label fw-bold">Select Staff</label>
                                <select name="staff_id" id="staff_id" class="form-select <?= $staff_id_err ? 'is-invalid' : '' ?>" required>
                                    <option value="" selected disabled>Choose your preferred staff</option>
                                    <?php if ($staff_res && $staff_res->num_rows > 0):
                                        while ($row = $staff_res->fetch_assoc()): ?>
                                            <option value="<?= $row['id'] ?>" <?= ($staff_id == $row['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($row['name']) ?>
                                            </option>
                                    <?php endwhile;
                                    endif; ?>
                                </select>
                                <?php if ($staff_id_err): ?>
                                    <div class="invalid-feedback"><?= $staff_id_err ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="appointment_date" class="form-label fw-bold">Appointment Date</label>
                                    <input type="date" name="appointment_date" id="appointment_date"
                                        class="form-control <?= $appointment_date_err ? 'is-invalid' : '' ?>"
                                        value="<?= htmlspecialchars($appointment_date) ?>" required>
                                    <?php if ($appointment_date_err): ?>
                                        <div class="invalid-feedback"><?= $appointment_date_err ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="appointment_time" class="form-label fw-bold">Appointment Time</label>
                                    <input type="time" name="appointment_time" id="appointment_time"
                                        class="form-control <?= $appointment_time_err ? 'is-invalid' : '' ?>"
                                        value="<?= htmlspecialchars($appointment_time) ?>" required>
                                    <?php if ($appointment_time_err): ?>
                                        <div class="invalid-feedback"><?= $appointment_time_err ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="comment" class="form-label fw-bold">Notes (Optional)</label>
                                <textarea name="comment" id="comment" class="form-control" rows="2"><?= htmlspecialchars($comment) ?></textarea>
                                <small class="text-muted">Any special notes for your appointment</small>
                            </div>

                            <div class="mb-4">
                                <label for="request" class="form-label fw-bold">Special Requests (Optional)</label>
                                <textarea name="request" id="request" class="form-control" rows="2"><?= htmlspecialchars($request) ?></textarea>
                                <small class="text-muted">Any special requirements you may have</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg py-3">
                                    <i class="fas fa-calendar-check me-2"></i> Confirm Appointment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p class="text-muted">Need help? <a href="#" class="text-primary">Contact our support team</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .service-summary {
        border-left: 4px solid #4e54c8;
        transition: all 0.3s ease;
    }

    .service-summary:hover {
        background-color: #f8f9fa !important;
        transform: translateX(5px);
    }

    .form-label {
        color: #495057;
    }

    .card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }

    .btn-primary {
        background-color: #4e54c8;
        border-color: #4e54c8;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #3a41b5;
        border-color: #3a41b5;
        transform: translateY(-2px);
    }

    .invalid-feedback {
        display: block;
    }
</style>

<script>
    // Client-side validation example
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>

<?php require '../layouts/footer.php'; ?>