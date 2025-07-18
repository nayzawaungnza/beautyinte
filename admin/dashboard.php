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
            <!-- Users Card -->
            <!-- Customers Card -->
            <div class="col-xl-3 col-md-3 mb-4">
                <div class="card shadow h-100 py-2 border-left-success">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">ဖောက်သည်</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_customers  ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Appointments Card -->
            <div class="col-xl-3 col-md-3 mb-4">
                <div class="card shadow h-100 py-2 border-left-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">အချိန်ချိန်းဆိုမှု</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_appointments  ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sales Card -->
            <div class="col-xl-3 col-md-3 mb-4">
                <div class="card shadow h-100 py-2 border-left-warning">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">အရောင်း</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_products ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-coins fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <div class="col-xl-3 col-md-3 mb-4">
                <div class="card shadow h-100 py-2 border-left-danger">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">ပရိုမိုးရှင်း</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_promotions  ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gift fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Content body end -->

<?php
require '../layouts/footer.php';
?>