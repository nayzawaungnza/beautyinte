<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;

$name_err = $email_err = $role_err = $phone_err = $gender_err = $salary_err = $position_err = '';
$name = $email = $role = $phone = $gender = $salary = $position = '';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT id, name, email, role, phone, gender, position, salary FROM users WHERE id = $id";
    $oldData = $mysqli->query($sql)->fetch_assoc();

    if ($oldData) {
        $name = $oldData['name'];
        $oldemail = $oldData['email'];
        $email = $oldemail;
        $role = $oldData['role'];
        $phone = $oldData['phone'];
        $gender = $oldData['gender'];
        $position = $oldData['position'];
        $salary = $oldData['salary'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_submit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $position = trim($_POST['position']);
    $salary = trim($_POST['salary']);

    // Name
    if (empty($name)) {
        $error = true;
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } else if (strlen($name) < 5) {
        $error = true;
        $name_err = "အမည်သည် အနည်းဆုံး စာလုံး ၅ လုံး ပြည့်မီရပါမည်။";
    } else if (strlen($name) > 100) {
        $error = true;
        $name_err = "အမည်သည် စာလုံး ၁၀၀ ထက်နည်းရပါမည်။";
    }

    // Email
    $email_pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $emailDuplicate = selectData("users", $mysqli, "WHERE email = '$email' AND id != $id", "*", "");

    if (empty($email)) {
        $error = true;
        $email_err = "ကျေးဇူးပြု၍ သင့်အီးမေးလ်ကိုဖြည့်ပါ။";
    } else if (!preg_match($email_pattern, $email)) {
        $error = true;
        $email_err = "အီးမေးလ် ဖော်မတ်မှားယွင်းနေပါသည်။";
    } else if ($emailDuplicate->num_rows > 0) {
        $error = true;
        $email_err = "ဤအီးမေးလ်သည် မှတ်ပုံတင်ပြီးသားဖြစ်ပါသည်။";
    }

    // Role
    // if (empty($role)) {
    //     $error = true;
    //     $role_err = "ကျေးဇူးပြု၍ အခန်းကဏ္ဍကို ရွေးချယ်ပါ။";
    // }

    // Phone
    if (empty($phone)) {
        $error = true;
        $phone_err = "ကျေးဇူးပြု၍ ဖုန်းနံပါတ်ထည့်ပါ။";
    } else if (strlen($phone) < 11) {
        $error = true;
        $phone_err = "ဖုန်းနံပါတ်သည် အနည်းဆုံးဂဏန်း ၁၁ လုံး ပြည့်မီရပါမည်။";
    }

    // Gender
    if ($gender === '') {
        $error = true;
        $gender_err = "ကျေးဇူးပြု၍ လိင် ရွေးချယ်ပါ။";
    }

    // Position
    if (empty($position)) {
        $error = true;
        $position_err = "Position ထည့်ရန်လိုအပ်ပါသည်။";
    }

    // Salary
    if (!is_numeric($salary) || $salary < 0) {
        $error = true;
        $salary_err = "Salary မှန်ကန်သော ငွေပမာဏဖြင့် ဖြည့်ပါ။";
    }

    if (!$error) {
        $stmt = $mysqli->prepare("UPDATE users SET name = ?, email = ?, role = ?, phone = ?, gender = ?, position = ?, salary = ? WHERE id = ?");
        $stmt->bind_param("ssssssdi", $name, $email, $role, $phone, $gender, $position, $salary, $id);
        $stmt->execute();

        echo "<script>window.location.href= 'user_list.php?success=အသုံးပြုသူ ပြင်ခြင်း အောင်မြင်ပါသည်'</script>";
        exit;
    }
}
?>

<!-- Content body start -->
<div class="content-body">
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">အသုံးပြုသူစာရင်း အသစ်ပြင်ခြင်း</h3>
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">အီးမေးလ်</label>
                        <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
                        <small class="text-danger"><?= $email_err ?></small>
                    </div>

                    <!-- <div class="form-group">
                        <label class="form-label">အခန်းကဏ္ဍ</label>
                        <select name="role" class="form-control">
                            <option value="">ရွေးချယ်ရန် အခန်းကဏ္ဍ</option>
                            <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>အုပ်ချုပ်သူ</option>
                            <option value="staff" <?= $role == 'staff' ? 'selected' : '' ?>>ဝန်ထမ်း</option>
                        </select>
                        <small class="text-danger"><?= $role_err ?></small>
                    </div> -->

                    <div class="form-group">
                        <label class="form-label">ဆက်သွယ်ရန်ဖုန်း</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>">
                        <small class="text-danger"><?= $phone_err ?></small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">လိင်</label><br />
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="male" <?= $gender == 'male' ? 'checked' : '' ?>>
                            <label class="form-check-label">ကျား</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" value="female" <?= $gender == 'female' ? 'checked' : '' ?>>
                            <label class="form-check-label">မ</label>
                        </div>
                        <small class="text-danger"><?= $gender_err ?></small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">ရာထူး (Position)</label>
                        <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($position) ?>">
                        <small class="text-danger"><?= $position_err ?></small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">လစာ (Salary)</label>
                        <input type="number" name="salary" class="form-control" value="<?= htmlspecialchars($salary) ?>">
                        <small class="text-danger"><?= $salary_err ?></small>
                    </div>

                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Content body end -->

<?php require '../layouts/footer.php'; ?>