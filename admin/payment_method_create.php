<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

$error = false;
$error_message = '';
$name = $status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $mysqli->real_escape_string(trim($_POST['name']));
    $status = isset($_POST['status']) ? 1 : 0;

    if ($name === '') {
        $error = true;
        $error_message = 'Please enter a payment method name.';
    } else {
        $sql = "INSERT INTO payment_method (name, status) VALUES ('$name', '$status')";
        $result = $mysqli->query($sql);
        if ($result) {
            header('Location: payment_method_list.php?success=Payment method created successfully');
            exit;
        } else {
            $error = true;
            $error_message = 'Failed to create payment method.';
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>Add Payment Method</h3>
            <a href="payment_method_list.php" class="btn btn-dark">Back to List</a>
        </div>
        <?php if ($error && $error_message) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="form-group mb-2">
                        <label for="name">Payment Method Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required />
                    </div>
                    <div class="form-group mb-2">
                        <label><input type="checkbox" name="status" value="1" <?= $status ? 'checked' : '' ?> /> Active</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Payment Method</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>