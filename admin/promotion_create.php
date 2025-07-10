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
        $error_message = 'Please fill all required fields and provide a valid discount.';
    } else {
        $sql = "INSERT INTO promotions (package_name, percentage, description, start_date, end_date) VALUES ('$title', '$discount_percent', '$description','$start_date', '$end_date')";
        $result = $mysqli->query($sql);
        if ($result) {
            header('Location: promotion_list.php?success=Promotion created successfully');
            exit;
        } else {
            $error = true;
            $error_message = 'Failed to create promotion.';
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>Add Promotion</h3>
            <a href="promotion_list.php" class="btn btn-dark">Back to List</a>
        </div>
        <?php if ($error && $error_message) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="form-group mb-2">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="discount_percent">Discount (%) <span class="text-danger">*</span></label>
                        <input type="number" name="discount_percent" id="discount_percent" class="form-control" value="<?= htmlspecialchars($discount_percent) ?>" min="1" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="start_date">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>" required />
                    </div>
                    <div class="form-group mb-2">
                        <label for="end_date">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Create Promotion</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>