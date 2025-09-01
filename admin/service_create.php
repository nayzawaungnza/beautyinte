<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name = $price = $description = $image = $time = '';
$name_err = $price_err = $description_err = $image_err = $time_err = '';
$category_id = '';
$category_err = '';

// Fetch categories
$category_result = $mysqli->query("SELECT id, name FROM service_categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_submit'])) {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $time = trim($_POST['time']);   // ✅ added
    $description = trim($_POST['description']);
    $category_id = trim($_POST['category_id']);

    // Validate name
    if (empty($name)) {
        $error = true;
        $name_err = "ကျေးဇူးပြု၍ အမည်ထည့်ပါ။";
    } elseif (strlen($name) > 1000) {
        $error = true;
        $name_err = "အမည်သည် စာလုံး ၁၀၀၀ ထက်နည်းရပါမည်။";
    }

    // Validate price
    if (empty($price)) {
        $error = true;
        $price_err = "ကျေးဇူးပြု၍ ဈေးနှုန်းထည့်ပါ။";
    } elseif (!is_numeric($price)) {
        $error = true;
        $price_err = "ဈေးနှုန်းသည် ဂဏန်းဖြစ်ရပါမည်။";
    }

    // Validate time
    if (empty($time)) {
        $error = true;
        $time_err = "ကျေးဇူးပြု၍ ကြာချိန် ထည့်ပါ။";
    }

    // Validate category
    if (empty($category_id)) {
        $error = true;
        $category_err = "ကျေးဇူးပြု၍ အမျိုးအစား ရွေးပါ။";
    }

    // Validate description
    if (empty($description)) {
        $error = true;
        $description_err = "ကျေးဇူးပြု၍ ဖော်ပြချက်ထည့်ပါ။";
    } elseif (strlen($description) > 1000) {
        $error = true;
        $description_err = "ဖော်ပြချက်သည် စာလုံး ၁၀၀၀ ထက်နည်းရပါမည်။";
    }

    // Handle image
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uplode/";
        $file_name = basename($_FILES['image']['name']);
        $new_file_name = time() . '_' . $file_name;
        $imageFileType = strtolower(pathinfo($new_file_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'jfif'];

        if (in_array($imageFileType, $allowed)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_file_name)) {
                $image = $new_file_name;
            } else {
                $error = true;
                $image_err = "ဓာတ်ပုံတင်ရန် မအောင်မြင်ပါ။";
            }
        } else {
            $error = true;
            $image_err = "ဓာတ်ပုံအမျိုးအစား မမှန်ပါ။";
        }
    }

    // Insert into DB
    if (!$error) {
        $stmt = $mysqli->prepare("INSERT INTO services (name, description, price, time, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdisi", $name, $description, $price, $time, $image, $category_id);
        $stmt->execute();
        $stmt->close();

        echo "<script>window.location.href= 'service_list.php?success=Service Created Successfully';</script>";
        exit();
    }
}
?>

<!-- Content body start -->
<div class="content-body">
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">ဝန်ဆောင်မှု အသစ်ဖန်တီးရန်</h3>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" style="color:black">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="price" style="color:black">စျေးနှုန်း</label>
                        <input type="text" name="price" class="form-control" value="<?= htmlspecialchars($price) ?>">
                        <small class="text-danger"><?= $price_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="time" style="color:black">ကြာချိန်</label>
                        <input type="text" name="time" class="form-control" value="<?= htmlspecialchars($time) ?>">
                        <small class="text-danger"><?= $time_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="category_id" style="color:black">အမျိုးအစား</label>
                        <select name="category_id" class="form-control">
                            <option value="">-- အမျိုးအစား ရွေးပါ --</option>
                            <?php while ($row = $category_result->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>" <?= $category_id == $row['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="text-danger"><?= $category_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="image" style="color:black">ပုံထည့်ပါ</label>
                        <input type="file" name="image" class="form-control">
                        <small class="text-danger"><?= $image_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="description" style="color:black">အကြောင်းအရာ ဖော်ပြချက်</label>
                        <textarea name="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
                        <small class="text-danger"><?= $description_err ?></small>
                    </div>

                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Content body end -->

<?php require '../layouts/footer.php'; ?>