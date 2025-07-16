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
        if($_FILES['image']['name']==$image){
            unlink("../uplode/".$image);
        }
        $target_dir = "../uplode/";
        $file_name = basename($_FILES['image']['name']);
        $target_file = time() . '_' . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'],  $target_dir . $target_file)) {
                $image = $target_file;
            } else {
                $msg = '<div class="alert alert-danger">Image upload failed.</div>';
            }
        } else {
            $msg = '<div class="alert alert-danger">Invalid image type.</div>';
        }
    }

    if ($msg == '') {
        $sql = "UPDATE users SET name='$name', email='$email', phone='$phone', role='$role', image = '$image' WHERE id=$admin_id";
        $result = $mysqli->query($sql);
        if ($result) {
            $msg = '<div class="alert alert-success">Profile updated successfully.</div>';
            // Refresh admin data
            $sql = "SELECT * FROM users WHERE id = '$admin_id'";
            $res = $mysqli->query($sql);
            $admin = $res->fetch_assoc();
        } else {
            $msg = '<div class="alert alert-danger">Failed to update profile.</div>';
        }
    }
}

require '../layouts/header.php';
?>
<div class="content-body p-5">
    <h2>Admin Profile</h2>
    <div class="row">
        <div class="col-md-6 col-sm-6 text-center">
            <div style="width: 200px; margin: auto; padding: 10px; border-radius: 10px; box-shadow: 2px 2px 2px 5px rgba(0, 0, 0, 0.3);">
                <img src="./uplode/<?php echo $admin['image'] ? $admin['image'] : 'https://via.placeholder.com/150'; ?>" class="img-fluid profile-img mb-3" alt="Profile Image">
            </div>
            <form method="post" enctype="multipart/form-data" class="mt-3">
                <div class="form-group">
                    <input type="file" name="image" class="form-control-file">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary btn-block mt-2">Update Image</button>
            
                </div>
                <div class="col-md-6 col-sm-6">
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
        </div>
    </div>
</div>

<?php require '../layouts/footer.php' ?>