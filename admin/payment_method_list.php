<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Handle delete
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $res = $mysqli->query("DELETE FROM payment_method WHERE id = $delete_id");
    if ($res) {
        header('Location: payment_method_list.php?success=Payment method deleted successfully');
        exit;
    } else {
        header('Location: payment_method_list.php?error=Failed to delete payment method');
        exit;
    }
}

$methods = $mysqli->query("SELECT * FROM payment_method ORDER BY id DESC");
require '../layouts/header.php';
?>
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>Payment Method List</h3>
            <a href="payment_method_create.php" class="btn btn-primary">Add Payment Method</a>
        </div>
        <?php if ($success) { ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php } ?>
        <?php if ($error) { ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php } ?>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($methods && $methods->num_rows > 0) {
                            while ($row = $methods->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= $row['status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
                                    <td>
                                        <a href="payment_method_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                                        <a href="#" data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete-btn">Delete</a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="4" class="text-center">No payment methods found.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'payment_method_list.php?delete_id=' + id;
                    }
                });
            });
        });
    });
</script>
<?php require '../layouts/footer.php'; ?>