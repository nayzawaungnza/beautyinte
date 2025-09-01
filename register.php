<?php
require_once('./require/db.php');
require './require/common.php';

$error = false;
$name_err =
    $email_err =
    $password_err =
    $phone_err =
    $gender_err =
    $name =
    $email =
    $password =
    $phone =
    $gender =
    $confirm_password =
    $confirm_password_err = '';

if (isset($_POST['btn_submit'])) {
    // Use null coalescing to safely get POST values
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';

    // Check if email already exists
    $sql = "SELECT * FROM `users` WHERE `email`= '" . $mysqli->real_escape_string($email) . "'";
    $result = $mysqli->query($sql);

    if ($result && $result->num_rows > 0) {
        $error = true;
        $email_err = "အီးမေးလ်သည် အသုံးပြုထားပြီး ဖြစ်သည်။";
    }

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
    } elseif ($password !== $confirm_password) {
        $error = true;
        $password_err = $confirm_password_err = "အတည်ပြုစကားဝှက် မကိုက်ညီပါ။";
    } else {
        $byScriptPassword = md5($password);
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
    if ($gender === '') {
        $error = true;
        $gender_err = "ကျေးဇူးပြု၍ လိင် ရွေးချယ်ပါ။";
    }

    if (!$error) {
        $stmt = $mysqli->prepare("INSERT INTO `users` (`name`, `email`, `password`, `role`, `phone`, `gender`) VALUES (?, ?, ?, 'customer', ?, ?)");
        $stmt->bind_param('sssss', $name, $email, $byScriptPassword, $phone, $gender);
        $stmt->execute();
        $stmt->close();

        echo "<script>window.location.href= 'http://localhost/Beauty/login.php' </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="my">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register | Beauty Salon</title>
    <link href="./dashCss/style.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e0c3fc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-card {
            max-width: 500px;
            margin: 2rem auto;
            border-radius: 1rem;
            box-shadow: 0 4px 32px rgba(80, 80, 120, 0.15);
            background: #fff;
        }

        .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c63ff;
        }

        .form-floating>.form-control {
            padding-left: 2.5rem;
        }

        .form-floating>label {
            left: 2.5rem;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
        }

        .error-container {
            height: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="register-card card p-4 mt-5">
            <div class="card-body">
                <h2 class="text-center mb-4 text-info">အကောင့်ဖွင့်ရန်</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="name" class="form-label">အမည်</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($name) ?>" />
                                </div>
                                <small class="text-danger"> <?= $name_err ?> </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="email" class="form-label">အီးမေးလ်</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($email) ?>" />
                                </div>
                                <small class="text-danger"> <?= $email_err ?> </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="password" class="form-label">စကားဝှက်</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control" id="password" value="<?= htmlspecialchars($password) ?>" />
                                </div>
                                <small class="text-danger"> <?= $password_err ?> </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="confirm_password" class="form-label">အတည်ပြုစကားဝှက်</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" value="<?= htmlspecialchars($confirm_password) ?>" />
                                </div>
                                <small class="text-danger"> <?= $confirm_password_err ?> </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="phone" class="form-label">ဆက်သွယ်ရန်ဖုန်း</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone" class="form-control" id="phone" value="<?= htmlspecialchars($phone) ?>" />
                                </div>
                                <small class="text-danger"> <?= $phone_err ?> </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">လိင်</label>
                            <div class="d-flex gap-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" <?= $gender === 'male' ? 'checked' : '' ?> disabled />
                                    <label class="form-check-label" for="genderMale">ကျား</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="female" <?= $gender === 'female' ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="genderFemale">မ</label>
                                </div>
                            </div>
                            <small class="text-danger"> <?= $gender_err ?> </small>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-2" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                        </div>
                        <div class="col-12">
                            <a href="login.php" class="login-link">အကောင့်ရှိပြီးသားလား? <span class="text-primary">လော့ဂ်အင် ဝင်ရန်</span></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <script src="./dashJs/common.min.js"></script>
    <script src="./dashJs/custom.min.js"></script>
    <script src="./dashJs/settings.js"></script>
    <script src="./dashJs/gleek.js"></script>
    <script src="./dashJs/styleSwitcher.js"></script>
</body>

</html>