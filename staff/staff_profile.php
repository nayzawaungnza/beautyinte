<?php
require '../require/check_auth.php';
checkAuth('staff');
require '../require/db.php';

$staff_id = $_SESSION['id'];
$msg = '';

// Fetch staff data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $staff_id);
$stmt->execute();
$result = $stmt->get_result();
$staff = $result->fetch_assoc();

// Handle profile update
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role']; // Staff role should not be changed
    $image = $staff['image'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../admin/uplode/";
        $file_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . time() . '_' . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
            } else {
                $msg = '<div class="alert alert-danger">Image upload failed.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Invalid image type.</div>';
        }
    }

    if ($msg == '') {
        $sql = "UPDATE users SET name=?, email=?, phone=?, image=? WHERE id=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ssssi', $name, $email, $phone, $image, $staff_id);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Profile updated successfully.</div>';
            // Refresh staff data
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('i', $staff_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $staff = $result->fetch_assoc();
        } else {
            $msg = '<div class="alert alert-danger">Failed to update profile.</div>';
        }
    }
}
require '../layouts/header.php';
?>
<div class="content-body p-5">
    <h2>Staff Profile</h2>
    <?php echo $msg; ?>
    <div class="row">
        <div class="col-md-6 col-sm-6 text-center">
            <div style="width: 200px; margin: auto; padding: 10px; border-radius: 10px; box-shadow: 2px 2px 2px 5px rgba(0, 0, 0, 0.3);">
                <img src="<?= $_SESSION['img'] ? '../uplode/' . $_SESSION['img'] : '../uplode/default.png' ?>" class="img-fluid profile-img mb-3" alt="Profile Image">
            </div>
            <form method="post" enctype="multipart/form-data" class="mt-3">
                <div class="form-group">
                    <input type="file" name="image" class="form-control-file">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary btn-block mt-2">Update Image</button>
            </form>
        </div>
        <div class="col-md-6 col-sm-6">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($staff['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($staff['phone']); ?>">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($staff['role']); ?>" readonly>
                </div>
                <button type="submit" name="update_profile" class="btn btn-success">Save Changes</button>
            </form>
            <hr>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php' ?>