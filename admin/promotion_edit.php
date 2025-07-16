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
$discount_err = 
$description_err  = 
$title_err =
$start_date_err = 
$error_message = '';
$title = $description = $discount_percent = $start_date = $end_date = $status = '';

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
    $discount_percent = $_POST['discount_percent'];
    $start_date = $mysqli->real_escape_string($_POST['start_date']);
    $end_date = $mysqli->real_escape_string($_POST['end_date']);

    if ($package_name === '' || $percentage <= 0 || $start_date === '' || $end_date === '') {
        $error = true;
        $error_message = 'ကျေးဇူးပြု၍ လိုအပ်သောအချက်များအားလုံးဖြည့်ပြီး မှန်ကန်သောလျှော့ဈေးကိုထည့်ပါ။';

    } 
    if ($start_date > $end_date) {
        $error = true;
        $start_date_err = 'Start date cannot be later than end date.';
    }
    if ($discount_percent < 1 || $discount_percent > 50) {
        $error = true;
       $discount_err = 'Discount percentage must be between 1 and 50.';
    }
    
    if ($package_name == "") {
        $error = true;
        $title_err = "Please add title.";
    } elseif (strlen($package_name) < 5) {
        $error = true;
        $title_err = "Title must be at least 5 characters.";
    } elseif (strlen($package_name) > 100) {
        $error = true;
        $title_err = "Title must be less than 100 characters.";
    }

    
    if (empty($description)) {
        $error = true;
        $description_err  = "Please add description";
    } else if (strlen($description) > 100) {
        $error = true;
        $description_err = 'Description must be less than 100 characters.';
    }
    
    
     if(!$error) {
        $sql = "UPDATE promotions SET package_name='$package_name', percentage='$percentage', description='$description', start_date='$start_date', end_date='$end_date' WHERE id=$promotion_id";
        $result = $mysqli->query($sql);
        if ($result) {
            header('Location: promotion_list.php?success=Promotion updated successfully');
            exit;
        } else {
            $error = true;
            $error_message = 'ပရိုမိုးရှင်းအသစ်ပြုလုပ်၍မရပါ။';
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid mt-3">

        <?php if ($error && $error_message) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">ပရိုမိုးရှင်း ပြင်ဆင်ရန်</h3>
                <form method="POST">
                    <div class="form-group mb-2">
                        <label for="package_name">ခေါင်းစဉ် <span class="text-danger"></span></label>
                        <input type="text" name="package_name" id="package_name" class="form-control" value="<?= htmlspecialchars($package_name) ?>" />
                         <small class="text-danger"><?= $title_err ?></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">အကြောင်းအရာ</label>
                        <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
                        <small class="text-danger"><?= $description_err ?></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="percentage">လျှော့စျေး<span class="text-danger"></span></label>
                         <input type="number" name="discount_percent" id="discount_percent" class="form-control" value="<?= htmlspecialchars($discount_percent) ?>" />
                        <small class="text-danger"><?= $discount_err ?></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="start_date">ပရိုမိုးရှင်း စသည့်ရက်<span class="text-danger"></span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>" />
                         <small class="text-danger"><?= $start_date_err ?></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="end_date">ပရိုမိုးရှင်း ပြီးဆုံးသည့်ရက်<span class="text-danger"></span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>" />
                    </div>
                    <button type="submit" class="btn btn-primary">ပရိုမိုးရှင်း အသစ်ဖန်တီးရန်</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>