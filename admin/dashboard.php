<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';
$total_customers = $mysqli->query("SELECT COUNT(*) as count FROM customers")->fetch_assoc()['count'];
$total_appointments = $mysqli->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'];
$total_products = $mysqli->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_promotions = $mysqli->query("SELECT COUNT(*) as count FROM promotions")->fetch_assoc()['count'];

?>

<!-- Content body start -->
<div class="content-body">
    <div class="container-fluid">
        <div class="row mt-4">
            <!-- Customers Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-green shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2" style="letter-spacing:1px;">ဖောက်သည်</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_customers  ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-friends fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Appointments Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-blue shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2" style="letter-spacing:1px;">အချိန်ချိန်းဆိုမှု</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_appointments  ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-orange shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2" style="letter-spacing:1px;">အရောင်း</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_products ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-coins fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Promotions Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-red shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2" style="letter-spacing:1px;">ပရိုမိုးရှင်း</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_promotions  ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gift fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .dashboard-card {
                border: none;
                border-radius: 1.5rem;
                transition: transform 0.2s, box-shadow 0.2s;
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }

            .dashboard-card:hover {
                transform: translateY(-6px) scale(1.03);
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            }

            .gradient-green {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }

            .gradient-blue {
                background: linear-gradient(135deg, #396afc 0%, #2948ff 100%);
            }

            .gradient-orange {
                background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%);
            }

            .gradient-red {
                background: linear-gradient(135deg, #f857a6 0%, #ff5858 100%);
            }

            .icon-glow {
                color: #fff;
                filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
            }
        </style>
    </div>
</div>
<!-- Content body end -->

<?php
require '../layouts/footer.php';
?>