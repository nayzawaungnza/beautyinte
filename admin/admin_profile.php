<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';


$admin_id = $_SESSION['id'];
$msg = '';

// Fetch admin data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle profile update
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $image = $admin['image'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uplode/";
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
        $sql = "UPDATE admins SET name=?, email=?, phone=?, role=?, image=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $name, $email, $phone, $role, $image, $admin_id);
        if ($stmt->execute()) {
            $msg = '<div class="alert alert-success">Profile updated successfully.</div>';
            // Refresh admin data
            $sql = "SELECT * FROM admins WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
        } else {
            $msg = '<div class="alert alert-danger">Failed to update profile.</div>';
        }
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if (password_verify($old_password, $admin['password'])) {
        if ($new_password === $confirm_password) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE admins SET password=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $hashed, $admin_id);
            if ($stmt->execute()) {
                $msg = '<div class="alert alert-success">Password changed successfully.</div>';
            } else {
                $msg = '<div class="alert alert-danger">Failed to change password.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">New passwords do not match.</div>';
        }
    } else {
        $msg = '<div class="alert alert-danger">Old password is incorrect.</div>';
    }
}
require '../layouts/header.php';
?>
<div class="content-body p-5">
    <h2>Admin Profile</h2>
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="<?php echo $admin['image'] ? $admin['image'] : 'https://via.placeholder.com/150'; ?>" class="profile-img mb-3" alt="Profile Image">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="image" class="form-control-file">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary btn-block mt-2">Update Image</button>
            </form>
        </div>
        <div class="col-md-8">
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($admin['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($admin['phone']); ?>">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($admin['role']); ?>" readonly>
                </div>
                <button type="submit" name="update_profile" class="btn btn-success">Save Changes</button>
            </form>
            <hr>
            <h4>Change Password</h4>
            <form method="post">
                <div class="form-group">
                    <label>Old Password</label>
                    <input type="password" name="old_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
            </form>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php' ?>