<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo '<div class="alert alert-danger">Invalid payment ID.</div>';
    exit;
}

// Fetch payment info
$sql = "SELECT p.*, a.id as appointment_id, c.name as customer_name, s.name as service_name,
 a.appointment_date, a.appointment_time FROM payments p INNER JOIN appointments a ON p.appointment_id = a.id INNER JOIN
  customers c ON a.customer_id = c.id INNER JOIN services s ON a.service_id = s.id ";
$result = $mysqli->query($sql);
if (!$result || $result->num_rows == 0) {
    // echo '<div class="alert alert-danger">Payment not found.</div>';
    exit;
}
$payment = $result->fetch_assoc();

$error = false;
$error_message = '';
$amount_error = $payment_method = $payment_method_error = $payment_date_error = '';
$amount = $payment['amount'];
$payment_method = $payment['payment_method_id'];
$payment_date = $payment['payment_date'];

if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $amount = $mysqli->real_escape_string($_POST['amount']);
    $payment_method = $mysqli->real_escape_string($_POST['payment_method']);
    $payment_date = $mysqli->real_escape_string($_POST['payment_date']);

    if ($amount === '' || !is_numeric($amount) || $amount <= 0) {
        $error = true;
        $amount_error = "ကျေးဇူးပြုပြီး မှန်ကန်သောငွေပမာဏကို ဖြည့်ပါ။";
    }
    if ($payment_method === '' || !in_array($payment_method, ['k-pay', 'wave-pay' , 'cash'])) {
        $error = true;
        $payment_method_error = "ကျေးဇူးပြုပြီး မှန်ကန်သော ငွေပေးချေမှုနည်းလမ်းကို ရွေးချယ်ပါ။";
    }
    if ($payment_date === '') {
        $error = true;
        $payment_date_error = "ကျေးဇူးပြုပြီး ငွေပေးချေမည့်နေ့ကို ရွေးချယ်ပါ။";
    }

    if (!$error) {
        $sql = "UPDATE payments SET amount='$amount', payment_method='$payment_method', payment_date='$payment_date'";
        $result = $mysqli->query($sql);
        if ($result) {
            echo "<script>window.location.href = '" . $admin_base_url . "payment_list.php?success=Payment Updated';</script>";
            exit;
        } else {
            $error = true;
            $error_message = "ငွေပေးချေမှု ပြင်ဆင်ခြင်း မအောင်မြင်ပါ။";
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid mt-3">
        <div class="d-flex justify-content-between">
            <h3 class="text-center mb-5 text-info">ငွေပေးချေမှု ပြင်ဆင်ရန်</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'payment_list.php' ?>" class="btn btn-dark">
                    နောက်သို့
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
                        <form action="" method="POST">
                            <div class="form-group mb-2">
                                <label class="form-label">အချိန်ချိန်းဆိုမှု</label>
                                <input type="text" class="form-control" value="(<?= $payment['appointment_id'] ?>) <?= $payment['customer_name'] ?> - <?= $payment['service_name'] ?> (<?= $payment['appointment_date'] ?>, <?= date('g:i A', strtotime($payment['appointment_time'])) ?>)" readonly />
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
                                    <option value="k-pay" <?= $payment_method == '1' ? 'selected' : '' ?>>K-Pay</option>
                                    <option value="wave-pay" <?= $payment_method == '2' ? 'selected' : '' ?>>Wave-Pay</option>
                                    <option value="cash" <?= $payment_method == '3' ? 'selected' : '' ?>>Cash</option>
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
                            <button type="submit" class="btn btn-primary w-100">အသစ်ဖန်တီးရန်</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>