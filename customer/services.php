<?php
require '../require/check_auth.php';
checkAuth('customer');
require '../layouts/header.php';

$sql = "SELECT * FROM `services`";
$result = $mysqli->query($sql);
?>
<div class="content-body">
    <div class="container py-5">
        <h2 class="text-center mb-5 fw-bold text-gradient-primary">ဝန်ဆောင်မှုများ</h2>
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($service = $result->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-0 service-card">
                            <div class="card-img-top service-image">
                                <div class="image-placeholder d-flex align-items-center justify-content-center">
                                    <i class="fas fa-concierge-bell fa-3x text-white"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-dark mb-3"><?= htmlspecialchars($service['name'] ?? 'Service') ?></h5>
                                <p class="card-text text-muted mb-4">
                                    <?= htmlspecialchars($service['description'] ?? ''); ?>
                                </p>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <?php if (!empty($service['price'])): ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="price-tag fw-bold text-success"><?= number_format($service['price'], 0) ?>ကျပ်</span>
                                        <a href="./appointment.php?service_id=<?= $service['id'] ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                            ဘိုကင်လုပ်ရန် <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .image-placeholder {
        height: 100%;
        width: 100%;
        background-color: rgba(0, 0, 0, 0.1);
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