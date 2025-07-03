<?php
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
    $sql = "SELECT users.id, users.name, users.email, users.password,users.role,users.phone,users.gender FROM  `users`";

    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
    $email = $oldData['email'];
    $password = $oldData['password'];
    $role = $oldData['role'];
    $phone = $oldData['phone'];
    $gender = $oldData['gender'];
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
        $name_err = "Please add name";
    } else if (strlen($name) < 5) {
        $error = true;
        $name_err = "Name must be fill greater than 5.";
    } else if (strlen($name) >= 10) {
        $error = true;
        $name_err = "Name must be fill less than 100.";
    }
    //Email
    $email_pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if (strlen($email) === 0) {
        $error = true;
        $email_err = "Please fill your email";
    } else if (strlen($email) > 20) {
        $error = true;
        $email_err = "Email must be less than 20.";
    } else if (strlen($email) > 200) {
        $error = true;
        $email_err = "Email must be less than 200.";
    } else if (!preg_match($email_pattern, $email)) {
        $error = true;
        $email_err = "Email format is wrong.";
    }
    //Password
    if (strlen($password) === 0) {
        $error = true;
        $password_err = "Please fill Password";
    } else if (strlen($password) < 8) {
        $error = true;
        $password_err = "Password must be greater than 8.";
    } else if (strlen($password) > 30) {
        $error = true;
        $password_err = "Password must be less than 30.";
    } else {
        $byScriptPassword = md5($password);
    }
    //role
    if (strlen($role) === 0 || $role === '') {
        $error = true;
        $role_err = "Please choose role";
    }
    //phone
    if (empty($phone)) {
        $error = true;
        $phone_err = "Please add phone";
    } else if (strlen($phone) < 11) {
        $error = true;
        $phone_err = "Phone must be fill greater than 11.";
    }
    //gender
    if ($gender === '') {
        $error = true;
        $gender_err = "Please choose gender";
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

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">အလှပြင်ဆိုင် စနစ်အနှစ်ချုပ်မျက်နှာပြင်</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">ပင်မစာမျက်နှာ</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>အသုံးပြုသူစာရင်း အသစ်ပြင်ခြင်း</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">အီးမေးလ်</label>
                        <input type="text" name="email" class="form-control" value="<?= $email ?>">
                        <small class="text-danger"><?= $email_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">စကားဝှက်</label>
                        <input type="password" name="password" class="form-control" value="<?= $password ?>">
                        <small class="text-danger"><?= $password_err ?></small>
                    </div>
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
                    <div class="form-group">
                        <label for="name" class="form-label">ဆက်သွယ်ရန်ဖုန်း</label>
                        <input type="text" name="phone" class="form-control" value="<?= $phone ?>">
                        <small class="text-danger"><?= $phone_err ?></small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">လိင်</label>
                        <br />
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault1" value="male">
                            <label class="form-check-label" for="flexRadioDefault1">
                                ယောကျာ်း
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="flexRadioDefault2" value="female">
                            <label class="form-check-label" for="flexRadioDefault2">
                                မိန်းမ
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