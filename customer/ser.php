<?php
require '../require/check_auth.php';
checkAuth('customer');
require '../layouts/header.php';

// Fetch categories for dropdown
$categories = selectData("service_categories", $mysqli, "", "*");

// Filter by category
$filter_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

$where = "";
if ($filter_category) {
    $where = "WHERE category_id = $filter_category";
}

$sql = "SELECT * FROM `service_categories` $where";
$result = $mysqli->query($sql);
?>

<div class="content-body" id="service-list">
    <div class="container py-5">
        <h2 class="text-center mb-4 fw-bold text-gradient-primary">ဝန်ဆောင်မှုအမျိုးအစားများ</h2>

        <!-- Category filter dropdown (optional) -->
        <!--
        <div class="row justify-content-center mb-5">
            <div class="col-md-4">
                <form method="GET" action="ser.php">
                    <select name="id"
                        class="form-control"
                        style="box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); border-radius: 10px; border: 1px solid #ccc; padding: 10px; font-size: 16px;"
                        onchange="this.form.submit()">
                        <option value="">-- ဝန်ဆောင်မှု အမျိုးအစားရွေးချယ်ရန် --</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($filter_category == $cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>
        </div>
        -->

        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($category = $result->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 mb-3 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 service-card">

                            <?php
                            // Build image path with fallback
                            $imagePath = !empty($category['image'])
                                ? "../uplode/" . htmlspecialchars($category['image'])
                                : "assets/images/default-service.png";
                            ?>

                            <!-- Image section -->
                            <div class="card-img-top service-image">
                                <img src="<?= $imagePath ?>"
                                    alt="<?= htmlspecialchars($category['name'] ?? 'Service') ?>"
                                    class="img-fluid w-100 h-100"
                                    style="object-fit: cover;">
                            </div>

                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark mb-3">
                                    <?= htmlspecialchars($category['name'] ?? 'Service') ?>
                                </h5>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <a href="./app.php?category_id=<?= $category['id'] ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                    အမျိုးအစားများကြည့်ရန် <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-4">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4 class="fw-bold">လက်ရှိတွင် ဝန်ဆောင်မှု မရှိပါ</h4>
                        <p class="mb-0">ဝန်ဆောင်မှုများအတွက် နောက်မှ ပြန်လာစစ်ဆေးပါ</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .text-gradient-primary {
        background: linear-gradient(to right, #4e54c8, #8f94fb);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .service-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .service-image {
        height: 150px;
        overflow: hidden;
    }

    .service-image img {
        display: block;
    }

    .price-tag {
        font-size: 1.1rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-footer {
        padding: 0 1.5rem 1.5rem;
    }
</style>

<?php require_once '../layouts/footer.php' ?>