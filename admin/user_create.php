<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name_err =
    $email_err =
    $password_err =
    $role_err   =
    $phone_err =
    $gender_err =
    $name =
    $email =
    $password =
    $role =
    $phone =
    $gender = '';

$image_err = '';
$image = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT users.id, users.name, users.email, users.password,users.role,users.phone,users.gender FROM  `users` WHERE users.id = '$id'";
    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
    $email = $oldData['email'];
    $password = $oldData['password'];
    $role = $oldData['role'];
    $phone = $oldData['phone'];
}

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];



    //Name
    if (empty($name)) {
        $error = true;
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } else if (strlen($name) < 5) {
        $error = true;
        $name_err = "အမည်သည် အနည်းဆုံး စာလုံး ၅ လုံး ပြည့်မီရပါမည်။";
    } else if (strlen($name) >= 100) {
        $error = true;
        $name_err = "အမည်သည် စာလုံး ၁၀၀ ထက်နည်းရပါမည်။";
    }
    //Email
    $email_pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (strlen($email) === 0) {
        $error = true;
        $email_err = "ကျေးဇူးပြု၍ သင့်အီးမေးလ်ကိုဖြည့်ပါ။";
    } else if (strlen($email) < 20) {
        $error = true;
        $email_err = "အီးမေးလ်သည် ၂၀ ထက်များရပါမည်။";
    } else if (strlen($email) > 200) {
        $error = true;
        $email_err = "အီးမေးလ်သည် ၂၀၀ ထက်နည်းရပါမည်။";
    } else if (!preg_match($email_pattern, $email)) {
        $error = true;
        $email_err = "အီးမေးလ် ဖော်မတ်မှားယွင်းနေပါသည်။";
    }
    //Password
    if (strlen($password) === 0) {
        $error = true;
        $password_err = "ကျေးဇူးပြု၍ လျှို့ဝှက်နံပါတ် ဖြည့်ပါ။";
    } else if (strlen($password) < 8) {
        $error = true;
        $password_err = "လျှို့ဝှက်နံပါတ်သည် အနည်းဆုံး ၈ လုံး ရှိရပါမည်။";
    } else if (strlen($password) > 30) {
        $error = true;
        $password_err = "လျှို့ဝှက်နံပါတ်သည် စာလုံး ၃၀ ထက်နည်းရပါမည်။";
    } else {
        $byScriptPassword = md5($password);
    }
    //role
    if (strlen($role) === 0 || $role === '') {
        $error = true;
        $role_err = "ကျေးဇူးပြု၍ အခန်းကဏ္ဍ ရွေးချယ်ပါ။";
    }
    //phone
    if (empty($phone)) {
        $error = true;
        $phone_err = "ကျေးဇူးပြု၍ ဖုန်းနံပါတ်ထည့်ပါ။";
    } else if (strlen($phone) < 11) {
        $error = true;
        $phone_err = "ဖုန်းနံပါတ်သည် အနည်းဆုံး ဂဏန်း ၁၁ လုံး ရှိရပါမည်။";
    }
    //gender
    if ($gender === '') {
        $error = true;
        $gender_err = "ကျေးဇူးပြု၍ လိင် ရွေးချယ်ပါ။";
    }

    // Image
    if (isset($_FILES['image'])) {
        $target_dir = "uplode/";
        $file_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . time() . '_' . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
            } else {
                $error = true;
                $image_err = "ဓာတ်ပုံတင်ရန် မအောင်မြင်ပါ။";
            }
        } else {
            $error = true;
            $image_err = "ဓာတ်ပုံအမျိုးအစား မမှန်ပါ။";
        }
    } else {
        $image = '';
    }

    if (!$error) {
        $sql = "INSERT INTO `users`(`name`, `email`, `password`, `role`, `phone`, `gender`, `image`)
     VALUES ('$name','$email','$byScriptPassword','$role','$phone','$gender','$image')";
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
                <h3 class="text-center mb-5 text-info">အသုံးပြုသူ အသစ်ဖန်တီးပါ</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">အမည်</label>
                                <input type="text" name="name" class="form-control" value="<?= $name ?>">
                                <small class="text-danger"><?= $name_err ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">အီးမေးလ်</label>
                                <input type="text" name="email" class="form-control" value="<?= $email ?>">
                                <small class="text-danger"><?= $email_err ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">စကားဝှက်</label>
                                <input type="password" name="password" class="form-control" value="<?= $password ?>">
                                <small class="text-danger"><?= $password_err ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-label">အခန်းကဏ္ဍ</label>
                                <select name="role" id="role" class="form-control" value="<?= $role ?>">
                                    <option value="">‌ေရွးချယ်ရန် အခန်းကဏ္ဍ</option>
                                    <option value="admin" <?php echo $role == 'admin' ? 'selected' : '' ?>>အုပ်ချုပ်သူ</option>
                                    <option value="staff" <?= $role == 'staff' ? 'selected' : '' ?>>ဝန်ထမ်း</option>
                                </select>

                                <?php
                                if ($role_err) {
                                ?>
                                    <small class="text-danger"><?php echo $role_err ?></small>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">ဆက်သွယ်ရန်ဖုန်း</label>
                                <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                                <small class="text-danger"><?= $phone_err ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">လိင်</label>
                                <br />
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" value="male">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        ကျား
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="female">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        မ
                                    </label>
                                </div>
                                <?php
                                if ($gender_err) {
                                ?>
                                    <br />
                                    <small class="text-danger"><?php echo $gender_err ?></small>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image" class="form-label">ပုံထည့်ပါ</label>
                                <input type="file" name="image" class="form-control">
                                <small class="text-danger"><?= isset($image_err) ? $image_err : '' ?></small>
                            </div>

                            <div class="my-2">
                                <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>

<!-- Content body end -->



<?php

require '../layouts/footer.php';

?>