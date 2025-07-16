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
    $res = $mysqli->query("DELETE FROM promotions WHERE id = $delete_id");
    if ($res) {
        header('Location: promotion_list.php?success=Promotion deleted successfully');
        exit;
    } else {
        header('Location: promotion_list.php?error=Failed to delete promotion');
        exit;
    }
}

$promotions = $mysqli->query("SELECT * FROM promotions ORDER BY id DESC");
require '../layouts/header.php';
?>
<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>ပရိုမိုးရှင်း စာရင်း</h3>
            <a href="promotion_create.php" class="btn btn-primary">ပရိုမိုးရှင်းထပ်ထည့်ရန်</a>
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
                            <th>စဥ်</th>
                            <th>ခေါင်းစဉ်</th>
                            <th>အကြောင်းအရာ</th>
                            <th>လျှော့စျေး</th>
                            <th>ပရိုမိုးရှင်းစသည့်ရက်</th>
                            <th>ပရိုမိုးရှင်းဆုံးသည့်ရက်</th>
                            <th>လုပ်ဆောင်မှု</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($promotions && $promotions->num_rows > 0) {
                            while ($row = $promotions->fetch_assoc()) { ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['package_name']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><?= htmlspecialchars($row['percentage']) ?></td>
                                    <td><?= htmlspecialchars($row['start_date']) ?></td>
                                    <td><?= htmlspecialchars($row['end_date']) ?></td>
                                    <td>
                                        <a href="promotion_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">ပြင်ဆင်ရန်</a>
                                        <a href="#" data-id="<?= $row['id'] ?>" class="btn btn-sm btn-danger delete-btn">ဖျက်ရန်</a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="8" class="text-center">ပရိုမိုးရှင်းများမရှိပါ</td>
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
                    title: 'ဖျက်မည်ဆိုတာသေချာပြီလား',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ဖျက်မည်',
                    cancelButtonText: 'မဖျက်ပါ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'promotion_list.php?delete_id=' + id;
                    }
                });
            });
        });
    });
</script>
<?php require '../layouts/footer.php'; ?>