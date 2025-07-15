<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

$error = false;
$error_message = '';
$title = $description = $discount_percent = $start_date = $end_date = $status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $mysqli->real_escape_string(trim($_POST['title']));
    $description = $mysqli->real_escape_string(trim($_POST['description']));
    $discount_percent = intval($_POST['discount_percent']);
    $start_date = $mysqli->real_escape_string($_POST['start_date']);
    $end_date = $mysqli->real_escape_string($_POST['end_date']);

    if ($title === '' || $discount_percent <= 0 || $start_date === '' || $end_date === '') {
        $error = true;
        $error_message = 'ကျေးဇူးပြု၍ လိုအပ်သောအချက်များအားလုံးဖြည့်ပြီး မှန်ကန်သောလျှော့ဈေးကိုထည့်ပါ။';
    } else {
        $sql = "INSERT INTO promotions (package_name, percentage, description, start_date, end_date) VALUES ('$title', '$discount_percent', '$description','$start_date', '$end_date')";
        $result = $mysqli->query($sql);
        if ($result) {
            header('Location: promotion_list.php?success=Promotion created successfully');
            exit;
        } else {
            $error = true;
            $error_message = 'ပရိုမိုးရှင်းဖန်တီးရန် မအောင်မြင်ပါ။';
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid mt-3">
        <!-- <div class="d-flex justify-content-between mb-3">
            
            <a href="promotion_list.php" class="btn btn-dark">ပြန်ရန်</a>
        </div> -->
        <?php if ($error && $error_message) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">ပရိုမိုးရှင်း ဖန်တီးရန်</h3>
                <form method="POST">
                    <div class="form-group mb-2">
                        <label for="title">ခေါင်းစဉ်<span class="text-danger"></span></label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">အကြောင်းအရာဖော်ပြချက်</label>
                        <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="discount_percent">လျှော့စျေး(%)<span class="text-danger"></span></label>
                        <input type="number" name="discount_percent" id="discount_percent" class="form-control" value="<?= htmlspecialchars($discount_percent) ?>" min="1" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="start_date">ပရိုမိုးရှင်း စသည့်ရက်<span class="text-danger"></span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="end_date">ပရိုမိုးရှင်း ပြီးဆုံးသည့်ရက်<span class="text-danger"></span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>" required />
                    </div>
                    <button type="submit" class="btn btn-primary">ပရိုမိုးရှင်း အသစ်ဖန်တီးရန်</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>