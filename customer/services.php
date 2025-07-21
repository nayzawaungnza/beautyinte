<?php
require '../require/check_auth.php';
checkAuth('customer');
require '../layouts/header.php';

$sql = "SELECT * FROM `services`";
$result = $mysqli->query($sql);
?>
<div class="content-body">
    <div class="container py-5">
        <h2 class="text-center mb-5 text-primary">ဝန်ဆောင်မှုများ</h2>
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($service = $result->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-info mb-2"><?= htmlspecialchars($service['name'] ?? 'ဝန်ဆောင်မှု') ?></h5>
                                <p class="card-text mb-3">
                                    <?= htmlspecialchars($service['description'] ?? ''); ?>
                                </p>
                                <?php if (!empty($service['price'])): ?>
                                    <div class="mt-auto d-flex justify-content-between">
                                        <span class="badge bg-success fs-6"><?= number_format($service['price'], 0) ?> Ks</span>
                                        <a href="./appointment.php?service_id=<?= $service['id'] ?>"><span class="badge bg-warning fs-6">go to Appointment</span></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">ဝန်ဆောင်မှုများ မရှိသေးပါ။</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require_once '../layouts/footer.php' ?>