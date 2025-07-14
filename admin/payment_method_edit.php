<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: payment_method_list.php?error=Invalid payment method ID');
    exit;
}
$method_id = intval($_GET['id']);

$error = false;
$error_message = '';

// Fetch current payment method data
$res = $mysqli->query("SELECT * FROM payment_method WHERE id = $method_id");
if (!$res || $res->num_rows === 0) {
    header('Location: payment_method_list.php?error=Payment method not found');
    exit;
}
$row = $res->fetch_assoc();

$name = $row['name'];
$status = $row['status'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $mysqli->real_escape_string(trim($_POST['name']));
    $status = isset($_POST['status']) ? 1 : 0;

    if ($name === '') {
        $error = true;
        $error_message = 'Please enter a payment method name.';
    } else {
        $sql = "UPDATE payment_method SET name='$name', status='$status' WHERE id=$method_id";
        $result = $mysqli->query($sql);
        if ($result) {
            header('Location: payment_method_list.php?success=Payment method updated successfully');
            exit;
        } else {
            $error = true;
            $error_message = 'Failed to update payment method.';
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>Edit Payment Method</h3>
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
                    <button type="submit" class="btn btn-primary">Update Payment Method</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require '../layouts/footer.php'; ?>