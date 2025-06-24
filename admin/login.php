<?php
require '../require/check_auth.php';
require '../require/db.php';
require '../require/common.php';
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
                $_SESSION['name'] = $data['name'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['role'] = $data['role'];
               header("Location: $admin_base_url".'dashboard.php');
            } else {
                $error = true;
                $password_error = "Password is incorrect.";
            }
        }
         else {
            $error = true;
            $email_error = "This email is not register.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log In</title>
  
    <!-- Favicon icon -->
    <!-- <<link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/favicon.png"> -->
    <link href="../dashCss/style.css" rel="stylesheet">
</head>

<body class="h-100" style=" background-color: #f5e4d7;">
    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content"> 
                        <?php if ($success !== '') { ?>
                            <div class="alert alert-success">
                                <?= $success ?>
                            </div>
                        <?php } ?>
                        <div class="card login-form mb-0 mt-5" style="background-color: #003366;">
                            <div class="card-body pt-5">
                                <a class="text-center" href="">
                                    <h1>Login Form</h1>
                                </a>

                                <form class="mt-5 mb-5 login-input" method="POST">
                                    <div class="form-group">
                                        <input type="email" class="form-control" placeholder="Email" name="email" value="<?= $email ?>" />
                                        <?php if ($error && $email_error) { ?>
                                            <span class="text-danger"><?= $email_error ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" placeholder="Password" name="password" value="<?= $password ?>" />
                                        <?php if ($error && $password_error) { ?>
                                            <span class="text-danger"><?= $password_error ?></span>
                                        <?php } ?>
                                    </div>
                                    <input type="hidden" name="form_sub" value="1" />
                                   <button class="btn login-form__btn submit w-100">Sign In</button>
                                </form>
                                <p class="mt-5 login-form__footer">Don't have account? <a href="<?= $admin_base_url ?>register.php" class="text-primary">Sign Up</a> now</p>
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
    <script src="../dashJs/common.min.js"></script>
    <script src="../dashJs/custom.min.js"></script>
    <script src="../dashJs/settings.js"></script>
    <script src="../dashJs/gleek.js"></script>
    <script src="../dashJs/styleSwitcher.js"></script>
</body>

</html>