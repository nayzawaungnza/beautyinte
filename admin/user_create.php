<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name_err = $email_err = $password_err = $role_err = $phone_err = $gender_err = '';
$position_err = $salary_err = '';

$name = $email = $password = $role = $phone = $gender = $position = '';
$salary = null;
$image = '';
$image_err = '';

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];

    // Name validation
    if (empty($name)) {
        $error = true;
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } elseif (strlen($name) < 5) {
        $error = true;
        $name_err = "အမည်သည် အနည်းဆုံး စာလုံး ၅ လုံး ပြည့်မီရပါမည်။";
    } elseif (strlen($name) >= 100) {
        $error = true;
        $name_err = "အမည်သည် စာလုံး ၁၀၀ ထက်နည်းရပါမည်။";
    }

    // Email validation
    $email_pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (strlen($email) === 0) {
        $error = true;
        $email_err = "ကျေးဇူးပြု၍ သင့်အီးမေးလ်ကိုဖြည့်ပါ။";
    } elseif (strlen($email) < 10) {
        $error = true;
        $email_err = "အီးမေးလ်သည် ၁၀ ထက်များရပါမည်။";
    } elseif (strlen($email) > 30) {
        $error = true;
        $email_err = "အီးမေးလ်သည်  ၃၀ ထက်နည်းရပါမည်။";
    } elseif (!preg_match($email_pattern, $email)) {
        $error = true;
        $email_err = "အီးမေးလ် ဖော်မတ်မှားယွင်းနေပါသည်။";
    }

    // Password validation
    if (strlen($password) === 0) {
        $error = true;
        $password_err = "ကျေးဇူးပြု၍ လျှို့ဝှက်နံပါတ် ဖြည့်ပါ။";
    } elseif (strlen($password) !== 8) {
        $error = true;
        $password_err = "လျှို့ဝှက်နံပါတ်သည် အတိုင်းအတာ ၈ လုံး ရှိရပါမည်။";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = true;
        $password_err = "လျှို့ဝှက်နံပါတ်တွင် အကြီးစာလုံး တစ်လုံး အနည်းဆုံး ပါဝင်ရပါမည်။";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = true;
        $password_err = "လျှို့ဝှက်နံပါတ်တွင် အသေးစာလုံး တစ်လုံး အနည်းဆုံး ပါဝင်ရပါမည်။";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = true;
        $password_err = "လျှို့ဝှက်နံပါတ်တွင် ဂဏန်း တစ်လုံး အနည်းဆုံး ပါဝင်ရပါမည်။";
    } elseif (!preg_match('/[\W_]/', $password)) { // Special characters or underscore
        $error = true;
        $password_err = "လျှို့ဝှက်နံပါတ်တွင် အထူးသင်္ကေတ တစ်လုံး အနည်းဆုံး ပါဝင်ရပါမည်။";
    } else {
        $byScriptPassword = md5($password);
    }

    // Role validation
    if (empty($role)) {
        $error = true;
        $role_err = "ကျေးဇူးပြု၍ အခန်းကဏ္ဍ ရွေးချယ်ပါ။";
    }

    // Phone validation
    if (empty($phone)) {
        $error = true;
        $phone_err = "ကျေးဇူးပြု၍ ဖုန်းနံပါတ်ထည့်ပါ။";
    } elseif (!preg_match('/^09\d{9}$/', $phone)) {
        $error = true;
        $phone_err = "ဖုန်းနံပါတ်သည် 09 ဖြင့်စ၍ ဂဏန်း ၁၁ လုံး အတိအကျ ရှိရပါမည်။";
    }

    // Gender validation
    if (empty($gender)) {
        $error = true;
        $gender_err = "ကျေးဇူးပြု၍ လိင် ရွေးချယ်ပါ။";
    }

    // Position (optional, but let's trim and validate length)
    if (!empty($position) && strlen($position) > 200) {
        $error = true;
        $position_err = "ရာထူးအမည်သည် စာလုံး 200 ထက်မပိုရပါ။";
    }

    // Salary (optional but must be integer if provided)
    if (!empty($salary)) {
        if (!is_numeric($salary)) {
            $error = true;
            $salary_err = "လစာသည် ဂဏန်းဖြစ်ရပါမည်။";
        }
    }

    // Image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uplode/";
        $file_name = basename($_FILES['image']['name']);
        $target_file = time() . '_' . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $target_file)) {
                $image = $target_file;
            } else {
                $error = true;
                $image_err = "ဓာတ်ပုံတင်ရန် မအောင်မြင်ပါ။";
            }
        } else {
            $error = true;
            $image_err = "ဓာတ်ပုံအမျိုးအစား မမှန်ပါ။";
        }
    }

    if (!$error) {
        $sql = "INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `gender`, `position`, `salary`, `image`) 
                VALUES ('$name','$email','$byScriptPassword','$role','$phone','$gender','$position','$salary','$image')";
        $mysqli->query($sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/user_list.php' </script>";
    }
}
?>

<!-- Content body start -->
<div class="content-body">
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">ဝန်ထမ်း အသစ်ဖန်တီးပါ</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">အမည်</label>
                                <input type="text" name="name" class="form-control" value="<?= $name ?>">
                                <small class="text-danger"><?= $name_err ?></small>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">အီးမေးလ်</label>
                                <input type="text" name="email" class="form-control" value="<?= $email ?>">
                                <small class="text-danger"><?= $email_err ?></small>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">စကားဝှက်</label>
                                <input type="password" name="password" class="form-control" value="<?= $password ?>">
                                <small class="text-danger"><?= $password_err ?></small>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">အခန်းကဏ္ဍ</label>
                                <select name="role" class="form-control">
                                    <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>အုပ်ချုပ်သူ</option>
                                    <option value="staff" <?= $role == 'staff' ? 'selected' : '' ?>>ဝန်ထမ်း</option>
                                    <option value="customer" <?= $role == 'customer' ? 'selected' : '' ?>>ဖောက်သည်</option>
                                </select>
                                <small class="text-danger"><?= $role_err ?></small>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">ဖုန်း</label>
                                <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                                <small class="text-danger"><?= $phone_err ?></small>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="col-md-6">
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
                        </div>

                        <!-- Position -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">လုပ်ငန်းကျွမ်းကျင်မှု</label>
                                <input type="text" name="position" class="form-control" value="<?= $position ?>">
                                <small class="text-danger"><?= $position_err ?></small>
                            </div>
                        </div>

                        <!-- Salary -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">လစာ (ကျပ်)</label>
                                <input type="text" name="salary" class="form-control" value="<?= $salary ?>">
                                <small class="text-danger"><?= $salary_err ?></small>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">ပုံထည့်ပါ</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-danger"><?= $image_err ?></small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 my-3 text-center">
                            <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>