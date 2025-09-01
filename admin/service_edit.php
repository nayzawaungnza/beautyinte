<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$error = false;
$name = $price = $description = $category_id = $time = '';
$name_err = $price_err = $description_err = $category_err = $time_err = '';

// Fetch categories
$category_result = $mysqli->query("SELECT id, name FROM service_categories");

// Fetch service data for editing
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $mysqli->prepare("SELECT id, name, price, time, description, category_id FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $oldData = $result->fetch_assoc();
    $stmt->close();

    if ($oldData) {
        $name = $oldData['name'];
        $price = $oldData['price'];
        $time = $oldData['time']; // ✅ fetch time
        $description = $oldData['description'];
        $category_id = $oldData['category_id'];
    } else {
        echo "<script>alert('ဝန်ဆောင်မှု မရှိပါ။'); window.location.href = 'service_list.php';</script>";
        exit();
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_submit'])) {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);
    $time = trim($_POST['time']);  // ✅ handle time
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
    } elseif ($price > 1000000) {
        $error = true;
        $price_err = "ဈေးနှုန်းသည် ၁,၀၀၀,၀၀၀ ကျပ်အောက်ဖြစ်ရပါမည်။";
    }

    // Validate time
    if (empty($time)) {
        $error = true;
        $time_err = "ကျေးဇူးပြု၍ ကြာချိန် ထည့်ပါ။";
    }

    // Validate description
    if (empty($description)) {
        $error = true;
        $description_err = "ကျေးဇူးပြု၍ ဖော်ပြချက်ထည့်ပါ။";
    } elseif (strlen($description) > 1000) {
        $error = true;
        $description_err = "ဖော်ပြချက်သည် စာလုံး ၁၀၀၀ ထက်နည်းရပါမည်။";
    }

    // Validate category
    if (empty($category_id)) {
        $error = true;
        $category_err = "အမျိုးအစား ရွေးပါ။";
    }

    // If valid, update
    if (!$error) {
        $stmt = $mysqli->prepare("UPDATE services SET name = ?, price = ?, time = ?, description = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("sdissi", $name, $price, $time, $description, $category_id, $id);
        $stmt->execute();
        $stmt->close();

        echo "<script>window.location.href= 'service_list.php?success=အသစ်ပြင်ခြင်း အောင်မြင်ပါသည်';</script>";
        exit();
    }
}
?>

<!-- Content body start -->
<div class="content-body">
    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <h3 class="text-center mb-5 text-info">ဝန်ဆောင်မှုပြင်ဆင်ခြင်း</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name">အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
                        <small class="text-danger"><?= $name_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="price">စျေးနှုန်း</label>
                        <input type="text" name="price" class="form-control" value="<?= htmlspecialchars($price) ?>">
                        <small class="text-danger"><?= $price_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="time">ကြာချိန်</label>
                        <input type="text" name="time" class="form-control" value="<?= htmlspecialchars($time) ?>">
                        <small class="text-danger"><?= $time_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="category_id">အမျိုးအစား</label>
                        <select name="category_id" class="form-control">
                            <option value="">-- အမျိုးအစား ရွေးပါ --</option>
                            <?php while ($cat = $category_result->fetch_assoc()): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <small class="text-danger"><?= $category_err ?></small>
                    </div>

                    <div class="form-group">
                        <label for="description">ဖော်ပြချက်</label>
                        <textarea name="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
                        <small class="text-danger"><?= $description_err ?></small>
                    </div>

                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">ပြင်ဆင်ပါ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Content body end -->

<?php require '../layouts/footer.php'; ?>