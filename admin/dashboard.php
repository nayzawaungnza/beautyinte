<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../layouts/header.php';

$august_sales = [
    'days' => [],
    'qtys' => []
];

// SQL query August 2025 အတွက် ရက်အလိုက် qty total တွေကိုယူမယ်
$query = "
    SELECT DAY(sale_date) AS day, SUM(qty) AS total_qty
    FROM product_sales
    WHERE MONTH(sale_date) = 8 AND YEAR(sale_date) = 2025
    GROUP BY day
    ORDER BY day
";

$result = $mysqli->query($query);

while ($row = $result->fetch_assoc()) {
    $august_sales['days'][] = (int)$row['day'];
    $august_sales['qtys'][] = (int)$row['total_qty'];
}

// Stats
$total_users = $mysqli->query("SELECT COUNT(*) as count FROM users WHERE role = 'staff'")->fetch_assoc()['count'];
$total_customers = $mysqli->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'];
$total_appointments = $mysqli->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'];
$total_promotions = $mysqli->query("SELECT COUNT(*) as count FROM promotions")->fetch_assoc()['count'];
$total_services = $mysqli->query("SELECT COUNT(*) as count FROM services")->fetch_assoc()['count'];

// Summary chart data
$summary_labels = ['ဝန်ထမ်းများ',  'အချိန်ချိန်းဆိုမှု'];
$summary_data = [$total_users,  $total_appointments];
?>

<!-- Content body start -->
<div class="content-body">
    <div class="container-fluid">
        <div class="row mt-4">
            <!-- Dashboard Cards -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-green shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2">ဝန်ထမ်းများ</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_users ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-friends fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-green shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2">ဖောက်သည်</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_customers ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-friends fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointments -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-blue shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2">အချိန်ချိန်းဆိုမှု</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_appointments ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-check fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Promotions -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-red shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2">ပရိုမိုးရှင်း</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_promotions ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gift fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card dashboard-card gradient-red shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-2">ဝန်ဆောင်မှုများ</div>
                                <div class="h3 mb-0 font-weight-bold text-white"><?= $total_services ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-gift fa-3x icon-glow"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals Bar Chart -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow border-0" style="max-width:900px; margin:auto;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-dark mb-4">
                            စုစုပေါင်း အချက်အလက်များ
                        </h5>
                        <canvas id="totalsChart" height="70" width="200"></canvas>
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

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const labels = ['ဝန်ထမ်း', 'ဖောက်သည်', 'ချိန်းဆိုမှု', 'ပရိုမိုးရှင်း', 'ဝန်ဆောင်မှု'];
            const data = [
                <?= $total_users ?>,
                <?= $total_customers ?>,
                <?= $total_appointments ?>,
                <?= $total_promotions ?>,
                <?= $total_services ?>
            ];

            const ctxTotals = document.getElementById('totalsChart').getContext('2d');
            new Chart(ctxTotals, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Counts',
                        data: data,
                        backgroundColor: [
                            '#4bc0c0', '#ff6384', '#36a2eb', '#ffcd56', '#9966ff', '#ff9f40'
                        ],
                        borderColor: '#333',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: ${ctx.parsed.y}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: ''
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: ''
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</div>
!--Content body end-- >

<?php require '../layouts/footer.php'; ?>