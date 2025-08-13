<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

if (!isset($_SESSION['payment_form_token'])) {
    $_SESSION['payment_form_token'] = uniqid();
}

$error = false;
$error_message = '';
$total_price = 0;
$promotion_id = '';
$promotion_percent = 0;
$promotions = [];
$appointment_id_error = $amount_error = $payment_method_id_error = '';
$appointment_id = $amount = $payment_method_id = $user_acc = $ph_no = '';
$payment_date = date("Y-m-d");
$sale_date = date("Y-m-d");  // Initialize sale_date with today
$appointment_date = $sale_date; // default fallback if appointment_date not fetched

// Fetch all promotions (for JS filtering)
$promotion_query = $mysqli->query("SELECT id, package_name, percentage, start_date, end_date FROM promotions WHERE percentage > 0");
if ($promotion_query && $promotion_query->num_rows > 0) {
    while ($prow = $promotion_query->fetch_assoc()) {
        $promotions[] = $prow;
    }
}

// Fetch payment methods
$payment_methods = $mysqli->query("SELECT id, name FROM payment_method WHERE status = 1 ORDER BY name ASC");

// Get appointment info from GET parameter
$displayText = "ID မပါရှိပါ";  // Default message

if (isset($_GET['id'])) {
    $appointment_id = (int)$_GET['id']; // sanitize input
    $result = $mysqli->query("SELECT a.*, c.name AS customer_name FROM appointments a INNER JOIN users c ON a.customer_id = c.id WHERE a.id = $appointment_id");
    if ($result && $row = $result->fetch_assoc()) {
        $displayText = htmlspecialchars($row['customer_name']) . " - " .
            htmlspecialchars($row['selected_service_names']) . " (" .
            htmlspecialchars($row['appointment_date']) . " " .
            htmlspecialchars($row['appointment_time']) . ")";

        // Assign appointment_date from DB to variable
        $appointment_date = $row['appointment_date'];

        // Calculate total price from selected_service_ids
        $service_ids = explode(',', $row['selected_service_ids']);
        $service_ids = array_map('intval', $service_ids);

        if (count($service_ids) > 0) {
            $in = implode(',', $service_ids);
            $price_result = $mysqli->query("SELECT SUM(price) AS total_price FROM services WHERE id IN ($in)");
            if ($price_result && $price_row = $price_result->fetch_assoc()) {
                $amount = (float) $price_row['total_price'];
            } else {
                $amount = 0;
            }
        } else {
            $amount = 0;
        }
    } else {
        $displayText = "Appointment မတွေ့ပါ";
        $appointment_id = '';
        $amount = 0;
    }
} else {
    $appointment_id = '';
    $amount = 0;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    if (isset($_POST['form_token']) && $_POST['form_token'] === $_SESSION['payment_form_token']) {
        $appointment_id = (int) $_POST['appointment_id'];
        $amount = (float) $_POST['amount'];
        $sale_date = $mysqli->real_escape_string($_POST['sale_date']);
        $promotion_id = isset($_POST['promotion_id']) ? $mysqli->real_escape_string($_POST['promotion_id']) : '';
        $payment_method_id = (int) $_POST['payment_method_id'];
        $promotion_percent = 0;
        if ($promotion_id) {
            $promo_row = $mysqli->query("SELECT percentage FROM promotions WHERE id='$promotion_id'")->fetch_assoc();
            if ($promo_row) $promotion_percent = $promo_row['percentage'];
        }

        if (!$appointment_id) {
            $error = true;
            $appointment_id_error = "ချိန်းဆိုမှုကို ရွေးချယ်ပါ။";
        }
        if ($amount <= 0) {
            $error = true;
            $amount_error = "မှန်ကန်သော ငွေပမာဏ ဖြည့်ပါ။";
        }
        if (!$payment_method_id) {
            $error = true;
            $payment_method_id_error = "ငွေပေးချေမှုနည်းလမ်းကို ရွေးချယ်ပါ။";
        }

        if (!$error) {
            $check_sql = "SELECT id FROM payments WHERE appointment_id = $appointment_id";
            $check_result = $mysqli->query($check_sql);

            if ($check_result && $check_result->num_rows > 0) {
                $error = true;
                $error_message = "ဤချိန်းဆိုမှုအတွက် ငွေပေးချေမှု ရှိပြီးဖြစ်ပါသည်။";
            } else {
                $sql = "INSERT INTO payments 
                        (appointment_id, amount, payment_method_id, payment_date, user_account, phone_number) 
                        VALUES ($appointment_id, $amount, $payment_method_id, '$sale_date', 'Admin','09457688317')";
                if ($mysqli->query($sql)) {
                    $_SESSION['payment_form_token'] = uniqid();
                    $payment_id = $mysqli->insert_id;
                    header("Location: {$admin_base_url}payment_voucher.php?id=" . $payment_id);
                    exit;
                } else {
                    $error = true;
                    $error_message = "ငွေပေးချေမှု မအောင်မြင်ပါ။";
                }
            }
        }
        unset($_SESSION['payment_form_token']);
    } else {
        header("Location: {$admin_base_url}payment_list.php?success");
        exit;
    }
}

require '../layouts/header.php';
?>

<!-- HTML form -->

<div class="content-body">
    <div class="container-fluid mt-3">
        <div class="d-flex justify-content-between">
            <h3 class="text-center mb-5 text-info">ငွေပေးချေမှုစာရင်း ဖန်တီးရန်</h3>
            <a href="<?= $admin_base_url . 'payment_list.php' ?>" class="btn btn-dark" style="height:45px;">နောက်သို့</a>
        </div>

        <?php if ($error && $error_message) { ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php } ?>

        <div class="card" style="width:700px; margin-left:80px;">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group mb-2">
                        <label for="appointment_info">ရယူခဲ့သောဝန်ဆောင်မှုများ</label>
                        <input type="text" name="appointment_info" id="appointment_info" class="form-control" value="<?= $displayText ?>" readonly>
                        <input type="hidden" name="appointment_id" value="<?= htmlspecialchars($appointment_id) ?>">
                        <?php if ($error && $appointment_id_error) { ?>
                            <span class="text-danger"><?= $appointment_id_error ?></span>
                        <?php } ?>
                    </div>

                    <div class="form-group mb-2">
                        <label for="amount">ငွေပမာဏ</label>
                        <input
                            type="number"
                            name="amount"
                            class="form-control"
                            id="amount"
                            value="<?= htmlspecialchars($amount) ?>"
                            min="0"
                            step="0.01"
                            readonly
                            data-original="<?= htmlspecialchars($amount) ?>" />

                        <?php if ($error && $amount_error) { ?>
                            <span class="text-danger"><?= $amount_error ?></span>
                        <?php } ?>
                    </div>

                    <div class="form-group mb-2">
                        <label for="payment_method_id">ငွေပေးချေမှုနည်းလမ်း</label>
                        <select name="payment_method_id" id="payment_method_id" class="form-control" required>
                            <option value="">ငွေပေးချေမှုနည်းလမ်း ရွေးချယ်ရန်</option>
                            <?php
                            if ($payment_methods && $payment_methods->num_rows > 0) {
                                // Reset pointer to start
                                $payment_methods->data_seek(0);
                                while ($row = $payment_methods->fetch_assoc()) {
                                    $selected = ($payment_method_id == $row['id']) ? 'selected' : '';
                                    echo "<option value='{$row['id']}' data-name='{$row['name']}' $selected>" . htmlspecialchars($row['name']) . "</option>";
                                }
                            }
                            ?>
                        </select>

                        <?php if ($error && $payment_method_id_error) { ?>
                            <span class="text-danger"><?= $payment_method_id_error ?></span>
                        <?php } ?>
                    </div>

                    <div id="cash-fields" style="display:none;">
                        <div class="form-group mb-3">
                            <label for="user_acc">အသုံးပြုသူအကောင့်</label>
                            <input type="text" name="user_acc" class="form-control" id="user_acc" value="Admin" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label for="ph_no">ဖုန်းနံပါတ်</label>
                            <input type="text" name="ph_no" class="form-control" id="ph_no" value="09457688317" disabled>
                        </div>
                    </div>

                    <div class="form-group mb-2" id="promotion-group" style="display:none;">
                        <label for="promotion_id" class="form-label">ပရိုမိုးရှင်း</label>
                        <select name="promotion_id" class="form-control" id="promotion_id">
                            <option value="">ပရိုမိုးရှင်း ရွေးချယ်ရန်</option>
                        </select>
                    </div>

                    <div class="form-group mb-2">
                        <label for="sale_date" class="form-label">ရက်စွဲ</label>
                        <input type="text" name="sale_date" class="form-control" id="sale_date" value="<?= htmlspecialchars($appointment_date) ?>" disabled />
                    </div>

                    <input type="hidden" name="form_sub" value="1" />
                    <input type="hidden" name="form_token" value="<?= $_SESSION['payment_form_token'] ?>" />
                    <button type="submit" class="btn btn-primary w-100">ငွေပေးချေမည်</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('payment_method_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const methodName = selectedOption.getAttribute('data-name');
        const cashFields = document.getElementById('cash-fields');

        if (methodName === 'K pay' || methodName === 'Wave Pay') {
            cashFields.style.display = 'block';
        } else {
            cashFields.style.display = 'none';
        }
    });
</script>

<script>
    const allPromotions = <?= json_encode($promotions) ?>;
    document.addEventListener('DOMContentLoaded', function() {
        const saleDateInput = document.getElementById('sale_date');
        const promotionGroup = document.getElementById('promotion-group');
        const promotionSelect = document.getElementById('promotion_id');
        const amountInput = document.getElementById('amount');

        let originalAmount = parseFloat(amountInput.value) || 0;

        function updatePromotionOptions() {
            promotionSelect.innerHTML = '<option value="">ပရိုမိုးရှင်း ရွေးချယ်ရန်</option>';
            const saleDate = saleDateInput.value;
            let hasPromotion = false;

            if (saleDate) {
                allPromotions.forEach(function(promo) {
                    if (saleDate >= promo.start_date && saleDate <= promo.end_date) {
                        const opt = document.createElement('option');
                        opt.value = promo.id;
                        opt.text = promo.package_name + ' (' + promo.percentage + '%)';
                        opt.setAttribute('data-percent', promo.percentage);
                        promotionSelect.appendChild(opt);
                        hasPromotion = true;
                    }
                });
            }
            promotionGroup.style.display = hasPromotion ? '' : 'none';
        }

        function updateAmountWithPromotion() {
            const selectedOption = promotionSelect.options[promotionSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const percent = parseFloat(selectedOption.getAttribute('data-percent'));
                if (percent > 0 && originalAmount > 0) {
                    const discounted = originalAmount * (1 - (percent / 100));
                    amountInput.value = discounted.toFixed(2);
                } else {
                    amountInput.value = originalAmount.toFixed(2);
                }
            } else {
                amountInput.value = originalAmount.toFixed(2);
            }
        }

        saleDateInput.addEventListener('change', function() {
            updatePromotionOptions();
            promotionSelect.value = '';
            amountInput.value = originalAmount.toFixed(2);
        });

        promotionSelect.addEventListener('change', updateAmountWithPromotion);

        updatePromotionOptions();
        updateAmountWithPromotion();
    });
</script>

<?php require '../layouts/footer.php'; ?>