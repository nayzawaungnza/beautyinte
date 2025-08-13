<?php
require '../require/check_auth.php';
checkAuth('customer');
require '../layouts/header.php';

$customer_id = $_SESSION['id'];

// Fetch all services
$services = [];
$service_res = $mysqli->query("SELECT id, name, price, description FROM services");
if ($service_res && $service_res->num_rows > 0) {
    while ($row = $service_res->fetch_assoc()) {
        $services[] = $row;
    }
}

// Fetch staff for dropdown
$staff_sql = "SELECT u.*
FROM users u
WHERE u.role = 'staff'
AND u.id NOT IN (
    SELECT a.staff_id
    FROM appointments a
    WHERE a.appointment_date = CURDATE()
    AND a.status = 3
)
ORDER BY u.name";
$staff_res = $mysqli->query($staff_sql);

// Form variables
$staff_id = $appointment_date = $appointment_time = $request = '';
$staff_id_err = $appointment_date_err = $appointment_time_err = $general_err = $request_err = '';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = isset($_POST['staff_id']) ? intval($_POST['staff_id']) : '';
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $appointment_time = trim($_POST['appointment_time'] ?? '');
    $request = trim($_POST['request'] ?? '');
    $selected_service_ids = $_POST['selected_service_ids'] ?? '';

    date_default_timezone_set('Asia/Yangon');
    $today = date('Y-m-d');
    $current_time = date('H:i:s');

    if (empty($selected_service_ids)) {
        $error = true;
        $general_err = 'ကျေးဇူးပြုပြီး ဝန်ဆောင်မှုတစ်ခုခန့် ရွေးချယ်ပါ။';
    }

    if (empty($staff_id) || !is_numeric($staff_id)) {
        $error = true;
        $staff_id_err = 'ကျေးဇူးပြုပြီး ဝန်ထမ်းတစ်ဦးကို ရွေးချယ်ပါ။';
    }

    if (empty($appointment_date)) {
        $error = true;
        $appointment_date_err = 'ကျေးဇူးပြုပြီး ချိန်းဆိုမည့်ရက်ကို ထည့်သွင်းပါ။';
    } elseif (strtotime($appointment_date) < strtotime($today)) {
        $error = true;
        $appointment_date_err = 'ရွေးချယ်ထားသောရက်သည် သက်တမ်းကုန်သွားပြီးပါပြီ။';
    }

    if (empty($request)) {
        $error = true;
        $request_err = "ကျေးဇူးပြု၍ တောင်းဆိုချက်ထည့်ပါ။";
    } elseif (strlen($request) > 1000) {
        $error = true;
        $request_err = "တောင်းဆိုချက်သည် စာလုံး ၁၀၀၀ ထက်နည်းရပါမည်။";
    }

    if (empty($appointment_time)) {
        $error = true;
        $appointment_time_err = 'ကျေးဇူးပြုပြီး ချိန်းဆိုချိန်ကို ထည့်သွင်းပါ။';
    } else {
        $time = strtotime($appointment_time);
        $start = strtotime('09:00');
        $end = strtotime('21:00');
        $lunch_start = strtotime('12:00');
        $lunch_end = strtotime('13:00');
        if ($time < $start || $time > $end) {
            $error = true;
            $appointment_time_err = 'ချိန်းဆိုချိန်သည် မနက် ၉:၀၀ မှ ည ၉:၀၀ အတွင်း ဖြစ်ရမည်။';
        } elseif ($time >= $lunch_start && $time < $lunch_end) {
            $error = true;
            $appointment_time_err = 'ချိန်းဆိုချိန်သည် နေ့လည် ၁၂:၀၀ မှ ၁:၀၀ အတွင်း မဖြစ်နိုင်ပါ။';
        } elseif ($appointment_date == $today && $appointment_time <= $current_time) {
            $error = true;
            $appointment_time_err = 'ချိန်းဆိုချိန် မရနိုင်ပါ။';
        }
    }

    // Check staff schedule conflict
    if (!$error) {
        $conflict_sql = "SELECT id FROM appointments WHERE staff_id = '$staff_id' AND appointment_date = '$appointment_date' AND appointment_time = '$appointment_time' AND (status = 0 OR status = 3)";
        $conflict_result = $mysqli->query($conflict_sql);
        if ($conflict_result && $conflict_result->num_rows > 0) {
            $error = true;
            $staff_id_err = 'ဤဝန်ထမ်းသည် လုပ်ဆောင်ရန်အလုပ်ရှိပြီးဖြစ်ပါသည်။ ယခုလက်ရှိအချိန်၏နောက်တစ်နာရီကြာမှ အချိန်ချိန်းဆိုမှုထပ်မံပြုလုပ်နိုင်ပါ။';
        }
    }

    // ✅ INSERT appointment with selected_service_ids & names
    if (!$error) {
        $customer_id_esc = $mysqli->real_escape_string($customer_id);
        $staff_id_esc = $mysqli->real_escape_string($staff_id);
        $appointment_date_esc = $mysqli->real_escape_string($appointment_date);
        $appointment_time_esc = $mysqli->real_escape_string($appointment_time);
        $request_esc = $mysqli->real_escape_string($request);

        $service_ids_array = explode(',', $selected_service_ids);
        $service_ids_cleaned = [];
        $service_names_cleaned = [];

        // Get service names from DB
        foreach ($service_ids_array as $service_id) {
            $service_id = trim($service_id);
            if (!empty($service_id) && is_numeric($service_id)) {
                $service_id_esc = $mysqli->real_escape_string($service_id);
                $res = $mysqli->query("SELECT name FROM services WHERE id = '$service_id_esc'");
                if ($res && $row = $res->fetch_assoc()) {
                    $service_ids_cleaned[] = $service_id;
                    $service_names_cleaned[] = $row['name'];
                }
            }
        }

        $service_ids_str = $mysqli->real_escape_string(implode(',', $service_ids_cleaned));
        $service_names_str = $mysqli->real_escape_string(implode(', ', $service_names_cleaned));

        // Insert into appointments
        $sql = "INSERT INTO appointments 
                (customer_id, staff_id, appointment_date, appointment_time, status,request, selected_service_ids, selected_service_names)
                VALUES 
                ('$customer_id_esc', '$staff_id_esc', '$appointment_date_esc', '$appointment_time_esc', 0,'$request_esc', '$service_ids_str', '$service_names_str')";

        $success_msg = '';

        if ($mysqli->query($sql)) {
            $inserted_id = $mysqli->insert_id; // အခု newly inserted row ID

            // ဒီ ID ကို အသုံးပြုပြီး data ပြန်ယူမယ်
            $res = $mysqli->query("SELECT * FROM appointments WHERE id = '$inserted_id'");

            if ($res && $row = $res->fetch_assoc()) {
                $appointment_data = $row;
            }

            $success_msg = 'ချိန်းဆိုမှု အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။';
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
                        <h3 class="mb-0 text-center">ချိန်းဆိုရန်</h3>
                    </div>
                    <?php if (!empty($success_msg) && !empty($appointment_data)): ?>
                        <script>
                            // JS alert box
                            alert("<?= addslashes($success_msg) ?>");

                            // Optional: Console log appointment data
                            console.log("Appointment Details: ", <?= json_encode($appointment_data) ?>);

                            // Redirect to dashboard
                            window.location.href = "dash.php";
                        </script>
                    <?php endif; ?>


                    <form method="POST" class="needs-validation" novalidate>
                        <!-- Add this inside your <form> tag -->
                        <input type="hidden" name="service_ids" id="selectedServices">
                        <input type="hidden" name="selected_service_ids" id="selectedServiceIds" value="<?= htmlspecialchars($_POST['selected_service_ids'] ?? '') ?>">
                        <input type="hidden" name="selected_service_names" id="selectedServiceNames" value="<?= htmlspecialchars($_POST['selected_service_names'] ?? '') ?>">

                        <div>
                            <div class="card-body p-4">
                                <label class="mb-2 fw-bold">ဝန်ဆောင်မှု ရွေးချယ်ရန်:</label>

                                <!-- Selected Names Display Input -->
                                <input type="text" id="selectedNames" class="form-control mb-3" readonly placeholder="">

                                <!-- Scrollable service card list -->
                                <div id="serviceList" style="max-height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 6px;">
                                    <?php if (!empty($services)): ?>
                                        <?php foreach ($services as $service): ?>
                                            <div class="service-card service-summary mb-3 p-2 bg-light rounded"
                                                data-id="<?= htmlspecialchars($service['id']) ?>"
                                                data-name="<?= htmlspecialchars($service['name']) ?>"
                                                style="cursor: pointer; border: 2px solid transparent;">
                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                    <div>
                                                        <strong><?= htmlspecialchars($service['name']) ?></strong>
                                                        <?php if (!empty($service['description'])): ?>
                                                            <small class="text-muted d-block"><?= htmlspecialchars($service['description']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="badge bg-success"><?= number_format($service['price'], 0) ?> Ks</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div>
                        <?php else: ?>
                            <div class="alert alert-danger text-center">ရွေးချယ်ထားသော ဝန်ဆောင်မှုကို မတွေ့ပါ</div>
                        <?php endif; ?>

                        <?php if ($general_err): ?>
                            <div class="alert alert-danger"><?= $general_err ?></div>
                        <?php endif; ?>


                        <div class="mb-4">
                            <label for="staff_id" class="form-label fw-bold" style="margin-left:20px;">ဝန်ထမ်းရွေးချယ်ရန် - </label>
                            <select name="staff_id" id="staff_id" style="padding:10px" class="form-select <?= $staff_id_err ? 'is-invalid' : '' ?>" required>
                                <option value="" selected disabled>ဝန်ဆောင်မှုပေးမည့် ဝန်ထမ်းကို ရွေးပါ</option>
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


                        <div class="col-6">
                            <label for="appointment_date" class="form-label fw-bold" style="margin-left:20px;">ချိန်းဆိုရက်</label>
                            <input type="date" style="margin-left:13px; width:450px;" name="appointment_date" id="appointment_date"
                                class="form-control <?= $appointment_date_err ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($appointment_date) ?>" required>
                            <?php if ($appointment_date_err): ?>
                                <div class="invalid-feedback"><?= $appointment_date_err ?></div>
                            <?php endif; ?>
                        </div>
                        <br>
                        <div class="col-6">
                            <label for="appointment_time" class="form-label fw-bold" style="margin-left:20px;">ချိန်းဆိုချိန်</label>
                            <input type="time" style="margin-left:13px; width:450px;" name="appointment_time" id="appointment_time"
                                class="form-control <?= $appointment_time_err ? 'is-invalid' : '' ?>"
                                value="<?= htmlspecialchars($appointment_time) ?>" required>
                            <?php if ($appointment_time_err): ?>
                                <div class="invalid-feedback"><?= $appointment_time_err ?></div>
                            <?php endif; ?>
                        </div>
                        <br>

                        <div class="mb-4">
                            <label for="request" class="form-label fw-bold" style="margin-left:20px;">တောင်းဆိုချက်</label>
                            <textarea name="request" id="request" class="form-control" rows="2" style="margin-left:23px; width:470px;"><?= htmlspecialchars($request) ?></textarea>
                            <small class="text-danger"><?= $request_err ?></small>
                            <br><br>
                            <div class="d-grid d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary" style="margin-left:20px; padding:16px;">
                                    <i class="fas fa-calendar-check me-2"></i> ချိန်းဆိုမှု အတည်ပြုရန်
                                </button>


                                <button type="submit" class="btn btn-primary" style="margin-right:20px; padding:16px;">
                                    <a href="ser.php" style="text-decoration:none;color:#f8f9fa;"> <i class="fas fa-calendar-check me-2"></i> ချိန်းဆိုမှု အတည်မပြုရန် </a>
                                </button>

                            </div>
                    </form>
                </div>
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

    /* .btn-primary:hover {
        background-color: #4e54c8;
        border-color: #3a41b5;
        transform: translateY(-2px);
    } */

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


<script>
    const selectedIds = [];
    const selectedNames = [];
    const selectedIdsInput = document.getElementById('selectedServiceIds');
    const selectedNamesInput = document.getElementById('selectedServiceNames');
    const serviceCards = document.querySelectorAll('.service-card');
    const selectedOutput = document.getElementById('selectedOutput');

    serviceCards.forEach(card => {
        card.addEventListener('click', () => {
            const id = card.getAttribute('data-id');
            const name = card.getAttribute('data-name');
            const index = selectedIds.indexOf(id);

            if (index > -1) {
                // Already selected – remove
                selectedIds.splice(index, 1);
                selectedNames.splice(index, 1);
                card.style.borderColor = 'transparent';
                card.classList.remove('bg-primary', 'text-dark');
            } else {
                // Not selected – add
                selectedIds.push(id);
                selectedNames.push(name);
                card.style.borderColor = '#0d6efd';
                card.classList.add('bg-primary', 'text-dark');
            }

            // Update inputs
            selectedIdsInput.value = selectedIds.join(',');
            selectedNamesInput.value = selectedNames.join(', ');
            document.getElementById('selectedNames').value = selectedNames.join(', ');

            // ✅ Update UI instead of console
            selectedOutput.innerText =
                'ရွေးချယ်ထားသော ဝန်ဆောင်မှု ID များ: ' + selectedIds.join(', ') +
                '\nဝန်ဆောင်မှုအမည်များ: ' + selectedNames.join(', ');
        });
    });
</script>


<?php require '../layouts/footer.php'; ?>