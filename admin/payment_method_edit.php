<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>window.location.href= 'payment_method_list.php'</script>";
    exit;
}

$method_id = intval($_GET['id']);
$error = false;
$error_message = '';

// Fetch current data
$res = $mysqli->query("SELECT * FROM payment_method WHERE id = $method_id");
if (!$res || $res->num_rows === 0) {
    echo "<script>window.location.href= 'payment_method_list.php'</script>";
    exit;
}
$row = $res->fetch_assoc();

$name = $row['name'];
$user_acc = $row['user_acc'];
$ph_no = $row['ph_no'];
$status = $row['status'];
$image = $row['image'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $mysqli->real_escape_string(trim($_POST['name']));
    $user_acc = $mysqli->real_escape_string(trim($_POST['user_acc']));
    $ph_no = $mysqli->real_escape_string(trim($_POST['ph_no']));
    $status = isset($_POST['status']) ? 1 : 0;

    // Image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uplode/";
        $imageName = time() . '_' . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $imageName;
        } else {
            $error = true;
            $error_message = "Image upload failed.";
        }
    }

    if ($name === '') {
        $error = true;
        $error_message = 'ကျေးဇူးပြု၍ ငွေပေးချေနည်းလမ်း အမည်ထည့်ပါ။';
    }

    if (!$error) {
        $sql = "UPDATE payment_method 
                SET name='$name', image='$image', user_acc='$user_acc', ph_no='$ph_no', status='$status' 
                WHERE id=$method_id";
        $result = $mysqli->query($sql);

        if ($result) {
            echo "<script>window.location.href= 'payment_method_list.php'</script>";
            exit;
        } else {
            $error = true;
            $error_message = 'အချက်အလက်များ ပြင်ဆင်ရာတွင် အမှားရှိနေပါသည်။';
        }
    }
}
?>

<div class="content-body">
    <div class="container-fluid mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-2 text-info">ငွေပေး‌ချေမှုနည်းလမ်း ပြင်ဆင်ခြင်း</h3>
            </div>
            <?php if ($error && $error_message) { ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
            <?php } ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group mb-2">
                            <label for="name">နည်းလမ်းအမည်</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required />
                        </div>
                        <div class="form-group mb-2">
                            <label for="user_acc">အကောင့်</label>
                            <input type="text" name="user_acc" id="user_acc" class="form-control" value="<?= htmlspecialchars($user_acc) ?>" />
                        </div>
                        <div class="form-group mb-2">
                            <label for="ph_no">ဖုန်းနံပါတ်</label>
                            <input type="text" name="ph_no" id="ph_no" class="form-control" value="<?= htmlspecialchars($ph_no) ?>" />
                        </div>
                        <div class="form-group mb-2">
                            <label>နည်းလမ်းပုံ (ပြောင်းလဲလိုပါက)</label><br>
                            <?php if ($image) { ?>
                                <img src="../uplode/<?= htmlspecialchars($image) ?>" width="100" class="mb-2" alt="payment image" /><br>
                            <?php } ?>
                            <input type="file" name="image" class="form-control-file" />
                        </div>
                        <div class="form-group mb-3">
                            <label><input type="checkbox" name="status" value="1" <?= $status ? 'checked' : '' ?> /> Active</label>
                        </div>
                        <button type="submit" class="btn btn-primary">ပြင်ဆင်ပြီးသိမ်းမည်</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php'; ?>