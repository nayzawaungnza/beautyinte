<?php
require '../require/db.php';
require '../require/common.php';
$error = false;
$error_message = '';
$appointment_id_error = $amount_error = $payment_method_error = $payment_date_error = '';
$appointment_id = $amount = $payment_method = $payment_date = '';

// Fetch appointments for dropdown (only completed and not already paid)
$appointments = $mysqli->query("SELECT a.id, c.name as customer_name, s.name as service_name, a.appointment_date, a.appointment_time, s.price as service_price FROM appointments a INNER JOIN customers c ON a.customer_id = c.id INNER JOIN services s ON a.service_id = s.id WHERE a.status = 1 AND a.id NOT IN (SELECT appointment_id FROM payments)");
// var_dump($appointments->fetch_assoc());
if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $appointment_id = $mysqli->real_escape_string($_POST['appointment_id']);
    $amount = $mysqli->real_escape_string($_POST['amount']);
    $payment_method = $mysqli->real_escape_string($_POST['payment_method']);
    $payment_date = $mysqli->real_escape_string($_POST['payment_date']);

    // Validation
    if ($appointment_id === '' || !is_numeric($appointment_id)) {
        $error = true;
        $appointment_id_error = "Please select an appointment.";
    }
    if ($amount === '' || !is_numeric($amount) || $amount <= 0) {
        $error = true;
        $amount_error = "Please enter a valid amount.";
    }
    if ($payment_method === '' || !in_array($payment_method, ['k-pay', 'wave-pay'])) {
        $error = true;
        $payment_method_error = "Please select a valid payment method.";
    }
    if ($payment_date === '') {
        $error = true;
        $payment_date_error = "Please select a payment date.";
    }

    if (!$error) {
        $sql = "INSERT INTO payments (appointment_id, amount, payment_method, payment_date) VALUES ('$appointment_id', '$amount', '$payment_method', '$payment_date')";
        $result = $mysqli->query($sql);
        if ($result) {
            $url = $admin_base_url . 'payment_list.php?success=Register Success';
            header("Location: $url");
            exit;
        } else {
            $error = true;
            $error_message = "Payment Create Fail.";
        }
    }
}
require '../layouts/header.php';
?>

<!--**********************************
            Content body start
        ***********************************-->
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <h3>ငွေပေးချေမှုစာရင်း ဖန်တီးရန်</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'payment_list.php' ?>" class="btn btn-dark">
                    ပြန်ရန်
                </a>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="col-md-6 col-sm-10 col-12">
                <?php if ($error && $error_message) { ?>
                    <div class="alert alert-danger">
                        <?= $error_message ?>
                    </div>
                <?php } ?>
                <div class="card">
                    <div class="card-body">
                        <form action="<?= $admin_base_url ?>payment_create.php" method="POST">
                            <div class="form-group mb-2">
                                <label for="appointment_id" class="form-label">အချိန်ချိန်းဆိုမှု</label>
                                <select name="appointment_id" class="form-control" id="appointment_id">
                                    <option value="">အချိန်ချိန်းဆိုမှု ရွေးချယ်ရန်</option>
                                    <?php if ($appointments && $appointments->num_rows > 0) {
                                        while ($row = $appointments->fetch_assoc()) {
                                            $selected = ($appointment_id == $row['id']) ? 'selected' : '';
                                            $time12 = '';
                                            if (isset($row['appointment_time'])) {
                                                $time12 = date('g:i A', strtotime($row['appointment_time']));
                                            }
                                            $service_price = isset($row['service_price']) ? $row['service_price'] : '';
                                            echo "<option value='{$row['id']}' $selected data-price='{$service_price}'>({$row['id']}) {$row['customer_name']} - {$row['service_name']} ({$row['appointment_date']} , {$time12})</option>";
                                        }
                                    } else {
                                        echo '<option value="">No appointments available</option>';
                                    } ?>
                                </select>
                                <?php if ($error && $appointment_id_error) { ?>
                                    <span class="text-danger"><?= $appointment_id_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-2">
                                <label for="amount" class="form-label">ငွေပမာဏ</label>
                                <input type="number" name="amount" class="form-control" id="amount" value="<?= htmlspecialchars($amount) ?>" min="1" />
                                <?php if ($error && $amount_error) { ?>
                                    <span class="text-danger"><?= $amount_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-2">
                                <label for="payment_method" class="form-label">ငွေပေးချေမှု အမျိုးအစား</label>
                                <select name="payment_method" class="form-control" id="payment_method">
                                    <option value="">အမျိုးအစား ရွေးချယ်ရန်</option>
                                    <option value="k-pay" <?= $payment_method == 'k-pay' ? 'selected' : '' ?>>K-Pay</option>
                                    <option value="wave-pay" <?= $payment_method == 'wave-pay' ? 'selected' : '' ?>>Wave-Pay</option>
                                </select>
                                <?php if ($error && $payment_method_error) { ?>
                                    <span class="text-danger"><?= $payment_method_error ?></span>
                                <?php } ?>
                            </div>
                            <div class="form-group mb-2">
                                <label for="payment_date" class="form-label">ငွေပေး‌ချေသည့် ရက်စွဲ</label>
                                <input type="date" name="payment_date" class="form-control" id="payment_date" value="<?= htmlspecialchars($payment_date) ?>" />
                                <?php if ($error && $payment_date_error) { ?>
                                    <span class="text-danger"><?= $payment_date_error ?></span>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="form_sub" value="1" />
                            <button type="submit" class="btn btn-primary w-100">အသစ်ထပ်တိုးရန်</button>
                        </form>
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
    document.addEventListener('DOMContentLoaded', function() {
        var appointmentSelect = document.getElementById('appointment_id');
        var amountInput = document.getElementById('amount');
        if (appointmentSelect && amountInput) {
            appointmentSelect.addEventListener('change', function() {
                var selected = appointmentSelect.options[appointmentSelect.selectedIndex];
                var price = selected.getAttribute('data-price');
                if (price) {
                    amountInput.value = price;
                } else {
                    amountInput.value = '';
                }
            });
        }
    });
</script>

<?php
require '../layouts/footer.php';
?>