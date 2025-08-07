<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name = $phone = $email = $password = $gender = $description = '';
$name_err = $phone_err = $email_err = $password_err = $gender_err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_submit'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $gender = $_POST['gender'] ?? '';
    $description = trim($_POST['description']);

    // Validate name
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

    // Validate email
    if (empty($email)) {
        $error = true;
        $email_err = "Email ထည့်ပါ။";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $email_err = "Email မမှန်ကန်ပါ။";
    }

    // Validate password
    if (empty($password)) {
        $error = true;
        $password_err = "Password ထည့်ပါ။";
    } elseif (strlen($password) < 6) {
        $error = true;
        $password_err = "Password အနည်းဆုံး ၆ လုံးရှိရမည်။";
    }

    // Validate gender
    if (empty($gender)) {
        $error = true;
        $gender_err = "ကျား / မ ကို ရွေးပါ။";
    }

    // If no errors, insert
    if (!$error) {
        $hashed_password = md5($password);

        // Escape all inputs to prevent SQL injection
        $name = $mysqli->real_escape_string($name);
        $phone = $mysqli->real_escape_string($phone);
        $email = $mysqli->real_escape_string($email);
        $description = $mysqli->real_escape_string($description);
        $gender = $mysqli->real_escape_string($gender);

        $sql = "INSERT INTO `users` (`name`, `phone`, `email`, `password`, `role`, `gender`, `description`)
            VALUES ('$name', '$phone', '$email', '$hashed_password', 'customer', '$gender', '$description')";
        $result = $mysqli->query($sql);
        if ($result) {
            echo "<script>window.location.href = 'customer_list.php?success=Customer created';</script>";
            exit;
        } else {
            if ($mysqli->errno === 1062) {
                $email_err = "Email သည်ထပ်နေသည်။";
            } else {
                $email_err = "မှားယွင်းမှုတစ်ခု ဖြစ်ပွားခဲ့သည်။";
            }
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

                    <div class="form-group">
                        <label>အီးမေးလ်</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                        <small class="text-danger"><?= $email_err ?></small>
                    </div>

                    <div class="form-group">
                        <label>စကားဝှက်</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-danger"><?= $password_err ?></small>
                    </div>

                    <div class="form-group">
                        <label>ကျား / မ</label>
                        <select name="gender" class="form-control">
                            <option value="">ရွေးချယ်ပါ</option>
                            <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>ကျား</option>
                            <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>မ</option>
                        </select>
                        <small class="text-danger"><?= $gender_err ?></small>
                    </div>

                    <div class="form-group">
                        <label>ဖော်ပြချက် (Optional)</label>
                        <textarea name="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
                    </div>

                    <button class="btn btn-primary mt-3" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>