<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: promotion_list.php?error=Invalid promotion ID');
    exit;
}
$promotion_id = intval($_GET['id']);

$error = false;
$error_message = '';

$res = $mysqli->query("SELECT * FROM promotions WHERE id = $promotion_id");
if (!$res || $res->num_rows === 0) {
    header('Location: promotion_list.php?error=Promotion not found');
    exit;
}
$row = $res->fetch_assoc();

$package_name = $row['package_name'];
$description = $row['description'];
$percentage = $row['percentage'];
$start_date = $row['start_date'];
$end_date = $row['end_date'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_name = $mysqli->real_escape_string(trim($_POST['package_name']));
    $description = $mysqli->real_escape_string(trim($_POST['description']));
    $percentage = intval($_POST['percentage']);
    $start_date = $mysqli->real_escape_string($_POST['start_date']);
    $end_date = $mysqli->real_escape_string($_POST['end_date']);

    if ($package_name === '' || $percentage <= 0 || $start_date === '' || $end_date === '') {
        $error = true;
        $error_message = 'Please fill all required fields and provide a valid discount.';
    } else {
        $sql = "UPDATE promotions SET package_name='$package_name', percentage='$percentage', description='$description', start_date='$start_date', end_date='$end_date' WHERE id=$promotion_id";
        $result = $mysqli->query($sql);
        if ($result) {
            header('Location: promotion_list.php?success=Promotion updated successfully');
            exit;
        } else {
            $error = true;
            $error_message = 'Failed to update promotion.';
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>ပရိုမိုးရှင်း ပြင်ဆင်ရန်</h3>
            <a href="promotion_list.php" class="btn btn-dark">ပြန်ရန်</a>
        </div>
        <?php if ($error && $error_message) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="form-group mb-2">
                        <label for="package_name">ခေါင်းစဉ် <span class="text-danger"></span></label>
                        <input type="text" name="package_name" id="package_name" class="form-control" value="<?= htmlspecialchars($package_name) ?>" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">အကြောင်းအရာ</label>
                        <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="percentage">လျှော့စျေး<span class="text-danger"></span></label>
                        <input type="number" name="percentage" id="percentage" class="form-control" value="<?= htmlspecialchars($percentage) ?>" min="1" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="start_date">ပရိုမိုးရှင်းစသည့်ရက်<span class="text-danger"></span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="end_date">ပရိုမိုးရှင်းပြီးဆုံးသည့်ရက် <span class="text-danger"></span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>" required />
                    </div>
                    <button type="submit" class="btn btn-primary">ပရိုမိုးရှင်း အသစ်ဖန်တီးရန်</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>