<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location:  http://localhost/Beauty/" . $_SESSION['role'] . "/dashboard.php");
    exit();
}

require './require/db.php';

$userSelect = "SELECT * FROM `users`";
$result = $mysqli->query($userSelect);
if ($result->num_rows == 0) {
    $admin_password = md5('password');
    $sql = "INSERT INTO `users`(`name`, `email`, `password`, `role`) VALUES ('Admin', 'admin@gmail.com', '$admin_password', 'admin')";
    $mysqli->query($sql);
}
require './require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = false;
$email =
    $password =
    $email_error =
    $password_error = '';
if (isset($_POST['form_sub']) && $_POST['form_sub'] == '1') {
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = $mysqli->real_escape_string($_POST['password']);
    if (strlen($email) === 0) {
        $error = true;
        $email_error = "Email is require.";
    }
    if (strlen($password) === 0) {
        $error = true;
        $password_error = "Password is require.";
    } else {
        $byscript_password = md5($password);
    }
    if (!$error) {
        $sql = "SELECT * FROM `users` WHERE email='$email'";
        $result =  $mysqli->query($sql);
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if ($data['password'] === $byscript_password) {
                $_SESSION['id'] = $data['id'];
                $_SESSION['name'] = $data['name'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['user_id'] = $data['id'];
                $_SESSION['img'] = $data['image'] ? $data['image'] : 'default.png';
                $_SESSION['last_activity'] = time();
                if ($_SESSION['role'] == "staff" && $data['role'] == "staff") {
                    header("Location: $staff_base_url" . 'dashboard.php');
                    exit();
                }
                if ($_SESSION['role'] == "customer" && $data['role'] == "customer") {
                    header("Location: $customer_base_url" . 'services.php');
                    exit();
                }
                header("Location: $admin_base_url" . 'dashboard.php');
                exit();
            } else {
                $error = true;
                $password_error = "Password is incorrect.";
            }
        } else {
            $error = true;
            $email_error = "This email is not register.";
        }
    }
}
?>
<!DOCTYPE html>
<html class="h-100" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>အကောင့်ဝင်ရန်</title>
    <!-- Favicon icon -->
    <!-- <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png"> -->
    <link href="./dashCss/style.css" rel="stylesheet">
</head>

<body class="h-100" style=" background-color: #f5e4d7;">
    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-12">
                    <div class="form-input-content">
                        <?php if ($success !== '') { ?>
                            <div class="alert alert-success">
                                <?= $success ?>
                            </div>
                        <?php } ?>
                        <div class="login-form-bg h-100">
                            <div class="container-fluid h-100 d-flex align-items-center justify-content-center">
                                <div class="col-md-6 col-lg-5">
                                    <?php if ($success !== '') { ?>
                                        <div class="alert alert-success">
                                            <?= $success ?>
                                        </div>
                                    <?php } ?>
                                    <div class="card shadow-lg border-0 rounded-4" style="background: #fffdf8;">
                                        <div class="card-body p-5">
                                            <h2 class="text-center mb-4" style="color: #b76e79;">အကောင့်ဝင်ရန်</h2>

                                            <form class="login-input" method="POST">
                                                <div class="form-group mb-4">
                                                    <label for="email" class="form-label" style="font-weight: 500;">📧 အီးမေးလ်</label>
                                                    <input type="email" class="form-control form-control-lg rounded-pill px-4" placeholder="အီးမေးလ်ဖြင့်ဝင်ရောက်ရန်" name="email" value="<?= $email ?>" />
                                                    <?php if ($error && $email_error) { ?>
                                                        <small class="text-danger"><?= $email_error ?></small>
                                                    <?php } ?>
                                                </div>

                                                <div class="form-group mb-4 position-relative">
                                                    <label for="password" class="form-label" style="font-weight: 500;">🔒 စကားဝှက်</label>
                                                    <div class="input-group">
                                                        <input type="password" id="password" class="form-control form-control-lg rounded-start-pill px-4" placeholder="စကားဝှက်ဖြင့်ဝင်ရောက်ရန်" name="password" value="<?= $password ?>" />
                                                        <span class="input-group-text bg-white border rounded-end-pill" onclick="togglePassword()" style="cursor: pointer;">
                                                            <i id="toggleIcon" class="fa-solid fa-eye">👁️ </i>
                                                        </span>
                                                    </div>
                                                    <?php if ($error && $password_error) { ?>
                                                        <small class="text-danger"><?= $password_error ?></small>
                                                    <?php } ?>
                                                </div>


                                                <input type="hidden" name="form_sub" value="1" />

                                                <button class="btn w-100 rounded-pill py-2" style="background-color: #b76e79; color: white; font-weight: bold;">
                                                    ဝင်မည်
                                                </button>
                                            </form>

                                            <div class="mt-3 text-center">
                                                <a href="./home.php" style="text-decoration: none; color: #555;">🏠 ပင်မစာမျက်နှာသို့ ပြန်သွားရန်</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>




    <!--**********************************
        Scripts
    ***********************************-->
    <script src="./dashJs/common.min.js"></script>
    <script src="./dashJs/custom.min.js"></script>
    <script src="./dashJs/settings.js"></script>
    <script src="./dashJs/gleek.js"></script>
    <script src="./dashJs/styleSwitcher.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>

</html>