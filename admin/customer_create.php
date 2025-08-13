<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name = $phone = '';
$name_err = $phone_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_submit'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    if (empty($name)) {
        $error = true;
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } elseif (strlen($name) < 5) {
        $error = true;
        $name_err = "အမည်သည် အနည်းဆုံး စာလုံး ၅ လုံးရှိရမည်။";
    }

    // Validate phone
    if (empty($phone)) {
        $error = true;
        $phone_err = "ဖုန်းနံပါတ် ထည့်ရန် လိုအပ်သည်။";
    } elseif (strlen($phone) < 8) {
        $error = true;
        $phone_err = "ဖုန်းနံပါတ်မှန်ကန်မှု မရှိပါ။";
    }

    // If no errors, insert
    if (!$error) {
        $hashed_password = md5($password);

        // Escape all inputs to prevent SQL injection
        $name = $mysqli->real_escape_string($name);
        $phone = $mysqli->real_escape_string($phone);
        $sql = "INSERT INTO `users` (`name`, `phone`, `role`)
        VALUES ('$name', '$phone', 'customer')";
        $result = $mysqli->query($sql);
        if ($result) {
            echo "<script>window.location.href = 'customer_list.php?success=Customer created';</script>";
            exit;
        }
    }
}
?>

<div class="content-body">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-4 text-info">ဖောက်သည်အသစ်ဖန်တီးရန်</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>

                    <div class="form-group">
                        <label>ဖုန်းနံပါတ်</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
                        <small class="text-danger"><?= $phone_err ?></small>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>