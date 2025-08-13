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


require '../layouts/header.php';
?>
<div class="content-body p-5">
    <h2>Admin Profile</h2>
    <br>
    <div class="row">
        <div class="col-md-6 col-sm-6 text-center">
            <div style="width: 200px; margin: auto; padding: 10px; border-radius: 10px; box-shadow: 2px 2px 2px 5px rgba(0, 0, 0, 0.3);">
                <img src="../uplode/<?php echo $admin['image'] ? $admin['image'] : '../uplode/default.png'; ?>" class="img-fluid profile-img mb-3" alt="Profile Image">
            </div>
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
            </form>
            <hr>
        </div>
    </div>
</div>

<?php require '../layouts/footer.php' ?>