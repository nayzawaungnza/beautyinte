<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$error_message = '';
$name = $user_acc = $ph_no = '';
$status = 1;
$image_name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $mysqli->real_escape_string(trim($_POST['name']));
    $user_acc = $mysqli->real_escape_string(trim($_POST['user_acc']));
    $ph_no = $mysqli->real_escape_string(trim($_POST['ph_no']));
    $status = isset($_POST['status']) ? 1 : 0;

    // Validate name
    if ($name === '') {
        $error = true;
        $error_message = 'ကျေးဇူးပြု၍ ငွေပေးချေနည်းလမ်း အမည်ထည့်ပါ။';
    }

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = $_FILES['image']['type'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = time() . '_' . basename($_FILES['image']['name']);

        if (!in_array($file_type, $allowed_types)) {
            $error = true;
            $error_message = 'အတည်ပြုထားသော ဓာတ်ပုံဖိုင်သာ တင်နိုင်ပါသည် (jpg, jpeg, png)';
        } else {
            $upload_path = '../uplode/' . $file_name;
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $image_name = $file_name;
            } else {
                $error = true;
                $error_message = 'ဓာတ်ပုံဖိုင်တင်ရာတွင် ပြဿနာတက်ခဲ့သည်။';
            }
        }
    }

    if (!$error) {
        $stmt = $mysqli->prepare("INSERT INTO payment_method (name, image, user_acc, ph_no, status) VALUES (?, ?, ?, ?, ?)");



        $stmt->bind_param("ssssi", $name, $image_name, $user_acc, $ph_no, $status);
        if ($stmt->execute()) {
            echo "<script>window.location.href= 'payment_method_list.php?success=အသုံးပြုသူ ပြင်ခြင်း အောင်မြင်ပါသည်'</script>";
            exit;
        } else {
            $error = true;
            $error_message = 'ငွေပေးချေနည်းလမ်း ဖန်တီးရန် မအောင်မြင်ပါ။';
        }
    }
}
?>

<div class="content-body">
    <div class="container-fluid mt-3">
        <div class="d-flex justify-content-between mb-3">
            <h3>ငွေပေး‌ချေမှု နည်းလမ်းဖန်တီးခြင်း</h3>
            <a href="payment_method_list.php" class="btn btn-dark">စာရင်းသို့ ပြန်သွားရန်</a>
        </div>

        <?php if ($error && $error_message) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php } ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="name">နည်းလမ်းအမည် <span class="text-danger"></span></label>
                        <input type="text" name="name" class="form-control" id="name" required value="<?= htmlspecialchars($name) ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="image">ပုံ</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group mb-3">
                        <label for="user_acc">အသုံးပြုသူအကောင့်</label>
                        <input type="text" name="user_acc" class="form-control" id="user_acc" value="<?= htmlspecialchars($user_acc) ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label for="ph_no">ဖုန်းနံပါတ်</label>
                        <input type="text" name="ph_no" class="form-control" id="ph_no" value="<?= htmlspecialchars($ph_no) ?>">
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="status" value="1" id="status" <?= $status ? 'checked' : '' ?>>
                        <label class="form-check-label" for="status">အသုံးပြုနေသည်</label>
                    </div>

                    <button type="submit" class="btn btn-primary">သိမ်းဆည်းမည်</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>