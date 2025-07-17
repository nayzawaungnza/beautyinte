<?php
require_once '../require/db.php';
require_once '../require/common.php';
require_once '../require/common_function.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>အလှပြင်ဆိုင်</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <link href="../dashCss/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link href="../dashCss/asColorPicker.css" rel="stylesheet">
    <link href="../dashCss/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="../dashCss/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="../dashCss/daterangepicker.css" rel="stylesheet">
    <link href="../dashCss/jquery-clockpicker.min.css" rel="stylesheet">
    <link href="../dashCss/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" />
    <script src="../dashJs/sweetalert2.all.min.js"></script>
    <script src="../dashJs/jquery.min.js"></script>
    <style>
        .header {
            background: #e455bcd1;
            color: #fff;
            box-shadow: 0 2px 8px rgba(161, 140, 209, 0.10);
            border-bottom: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1030;
        }

        .header-content {
            min-height: 64px;
            color: #fff;
        }

        .brand-title h4 {
            color: #fff !important;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .input-group.icons input {
            background: #f3eaff;
            border-radius: 20px 0 0 20px;
            border: none;
        }

        .input-group.icons .input-group-text {
            background: #f3eaff;
            border-radius: 0 20px 20px 0;
            border: none;
        }

        .header .dropdown-toggle {
            color: #fff !important;
        }

        .header .dropdown-menu {
            min-width: 180px;
        }

        .nk-sidebar {
            background: #f89cde;
            min-width: 220px;
            max-width: 220px;
            min-height: 100vh;
            padding-top: 0;
            border-right: 2px solid #a18cd1;
        }

        .metismenu {
            padding-left: 0;
            background: #f89cde !important;
        }

        .metismenu>li {
            margin-bottom: 4px;

        }

        .metismenu a * {
            color: #fff;
        }

        .metismenu a {
            /* color: #fff; */
            color: #5f4b8b;
            font-size: 1rem;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s, border-left 0.2s;
            font-weight: 500;
            border-left: 4px solid transparent;
        }

        .metismenu a:hover,
        .metismenu>li.active>a {
            background: #e0c3fc;
            color: #5f4b8b;
            border-left: 4px solid #a18cd1;
        }

        .metismenu i {
            margin-right: 10px;
            font-size: 1.2em;
        }

        .metismenu .nav-text {
            font-weight: 500;
        }

        @media (max-width: 991px) {
            .nk-sidebar {
                position: absolute;
                z-index: 1000;
                min-height: 100vh;
                left: 0;
                top: 0;
                display: none;
            }
        }

        .main-content-with-header {
            padding-top: 72px;
        }
    </style>
</head>

<body>
    <div id="main-wrapper" style="position: relative; min-height: 100vh;">
        <!-- Header start -->
        <div class="header">
            <div class="header-content d-flex align-items-center justify-content-between px-4 py-2">
                <div class="d-flex align-items-center">
                    <button id="sidebarToggle" class="navbar-hamburger mr-3" style="background:none; border:none; font-size:28px; cursor:pointer; color:#2d3748;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="brand-title d-flex align-items-center">
                        <img src="../images/logo.jpg" alt="logo" style="width: 40px; border-radius: 8px; margin-right: 10px;">
                        <h4 class="mb-0">S&H အမျိုးသမီးသီးသန့် အလှပြုပြင်ရေး</h4>
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <!-- <div class="input-group icons mr-3" style="max-width: 250px;">
                        <input type="search" class="form-control border-0 shadow-none" placeholder="ရှာရန်" aria-label="Search Dashboard">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-search"></i></span>
                        </div>
                    </div> -->

                    <!-- <form action="/Beauty/admin/user_list.php" method="get" class="mb-0">
                        <div class="input-group icons mr-3" style="max-width: 250px;">
                            <input
                                type="search"
                                name="search"
                                class="form-control border-0 shadow-none"
                                placeholder="ရှာရန်"
                                aria-label="Search Dashboard"
                                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            <div class="input-group-append">
                                <button class="input-group-text bg-transparent border-0" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= $_SESSION['img'] ? '../uplode/' . $_SESSION['img'] : '../uplode/default.png' ?>" height="40" width="40" class="rounded-circle mr-2" alt="">
                            <span class="d-none d-md-inline">Profile</span>
                        </a>
                        <?php
                        if ($_SESSION['role'] == "staff") {
                            $profile_link = "http://localhost/Beauty/staff/staff_profile.php";
                        } else {
                            $profile_link = "http://localhost/Beauty/admin/admin_profile.php";
                        }
                        ?>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item" href="<?= $profile_link ?>"><i class="fas fa-user mr-2"></i> ပရိုဖိုင်</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= '../logout.php' ?>"><i class="fas fa-sign-out-alt mr-2"></i> အကောင့်ထွက်ရန်</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Header end -->
        <!-- Sidebar start -->
        <div class="nk-sidebar">
            <div class="nk-nav-scroll">
                <ul class="metismenu">
                    <?php
                    if ($_SESSION['role'] == "staff") { ?>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fas fa-tasks"></i><span class="nav-text">ဝန်ထမ်းတာဝန်များ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../staff/task_list.php">စာရင်း</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-solid fa-user"></i><span class="nav-text">အသုံးပြုသူများ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/user_list.php">စာရင်း</a></li>
                                <li><a href="../admin/user_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-solid fa-circle-user"></i><span class="nav-text">ဖောက်သည်</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/customer_list.php">စာရင်း</a></li>
                                <li><a href="../admin/customer_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-solid fa-bell-concierge"></i><span class="nav-text">ဝန်ဆောင်မှုများ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/service_list.php">စာရင်း</a></li>
                                <li><a href="../admin/service_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-solid fa-boxes-packing"></i><span class="nav-text">ရောင်းရန်ပစ္စည်းများ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/product_list.php">စာရင်း</a></li>
                                <li><a href="../admin/product_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-solid fa-money-check-dollar"></i><span class="nav-text">ငွေပေးချေမှုနည်းလမ်း</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/payment_method_list.php">စာရင်း</a></li>
                                <li><a href="../admin/payment_method_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-regular fa-calendar-check"></i><span class="nav-text">အချိန်ချိန်းဆိုမှုစာရင်း</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/appointment_list.php">စာရင်း</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-solid fa-credit-card"></i><span class="nav-text">ငွေပေးချေမှု</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/payment_list.php">စာရင်း</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa fa-gift"></i><span class="nav-text">ပရိုမိုးရှင်း</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/promotion_list.php">စာရင်း</a></li>
                                <li><a href="../admin/promotion_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="fa-solid fa-coins"></i><span class="nav-text">ပစ္စည်းအရောင်း</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/product_sale_list.php">စာရင်း</a></li>
                                <li><a href="../admin/product_sale_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <!-- Sidebar end -->
        <div class="main-content-with-header">
            <script>
                $(document).ready(function() {
                    $('#sidebarToggle').on('click', function() {
                        $('.nk-sidebar').toggle();
                    });
                });
            </script>