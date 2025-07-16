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

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT users.id, users.name, users.email, users.password,users.role,users.phone,users.gender FROM  `users` where id = '$id'";

    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
    $oldemail = $oldData['email'];
    $role = $oldData['role'];
    $phone = $oldData['phone'];
    $gender = $oldData['gender'];
}

if (isset($_POST['name']) && isset($_POST['btn_submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
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
    $emailDuplicate =  selectData("users", $mysqli, "where `email`= '$email'", "*", "");

    if (strlen($email) === 0) {
        $error = true;
        $email_err = "ကျေးဇူးပြု၍ သင့်အီးမေးလ်ကိုဖြည့်ပါ။";
    } else if (strlen($email) < 10) {
        $error = true;
        $email_err = "အီးမေးလ်သည် ၁၀ ထက်များရပါမည်။";
    } else if (strlen($email) > 30) {
        $error = true;
        $email_err = "အီးမေးလ်သည် ၃၀ ထက်နည်းရပါမည်။";
    } else if (!preg_match($email_pattern, $email)) {
        $error = true;
        $email_err = "အီးမေးလ် ဖော်မတ်မှားယွင်းနေပါသည်။";
    }
    if ($oldemail !== $email) {
        if ($emailDuplicate->num_rows > 0) {
            $error = true;
            $email_err = "ဤအီးမေးလ်သည် မှတ်ပုံတင်ပြီးသားဖြစ်ပါသည်။";
            $oldemail = $email;
        }
    }
    //role
    if (strlen($role) === 0 || $role === '') {
        $error = true;
        $role_err = "ကျေးဇူးပြု၍ အခန်းကဏ္ဍကို ရွေးချယ်ပါ။";
    }
    //phone
    if (empty($phone)) {
        $error = true;
        $phone_err = "ကျေးဇူးပြု၍ ဖုန်းနံပါတ်ထည့်ပါ။";
    } else if (strlen($phone) < 11) {
        $error = true;
        $phone_err = "ဖုန်းနံပါတ်သည် အနည်းဆုံးဂဏန်း ၁၁ လုံး ပြည့်မီရပါမည်။";
    }
    //gender
    if ($gender === '') {
        $error = true;
        $gender_err = "ကျေးဇူးပြု၍ လိင် ရွေးချယ်ပါ။";
    }

    if (!$error) {

        $sql = "UPDATE `users` SET 
        `users`.`name` = '$name', `users`.`email` = '$email', `users`.`password` = '$password', `users`.`role` = '$role', `users`.`phone` = '$phone', `users`.`gender` = '$gender'
        WHERE `users`.`id` = '$id'";
        $mysqli->query($sql);
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/user_list.php? success=အသစ်ပြင်ခြင်း အောင်မြင်ပါသည်' </script>";
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
                        <label for="name" class="form-label">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">အီးမေးလ်</label>
                        <input type="text" name="email" class="form-control" value="<?= $oldemail ?>">
                        <small class="text-danger"><?= $email_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label">အခန်းကဏ္ဍ</label>
                        <select name="role" id="role" class="form-control" value="<?= $role ?>">
                            <option value="">ရွေးချယ်ရန် အခန်းကဏ္ဍ</option>
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
                    <div class="form-group">
                        <label for="name" class="form-label">ဆက်သွယ်ရန်ဖုန်း</label>
                        <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                        <small class="text-danger"><?= $phone_err ?></small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">လိင်</label>
                        <br />
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" value="male" <?= $gender == 'male' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="flexRadioDefault1">
                                ကျား
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="female" <?= $gender == 'female' ? 'checked' : '' ?>>
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
                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
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