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
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Custom Stylesheet -->
    <link href="../dashCss/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="../dashCss/asColorPicker.css" rel="stylesheet">
    <!-- Color picker plugins css -->
    <link href="../dashCss/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- Date picker plugins css -->
    <link href="../dashCss/bootstrap-timepicker.min.css" rel="stylesheet">
    <!-- Daterange picker plugins css -->
    <link href="../dashCss/daterangepicker.css" rel="stylesheet">
    <link href="../dashCss/jquery-clockpicker.min.css" rel="stylesheet">

    <link href="../dashCss/style.css" rel="stylesheet">
    <script src="../dashJs/sweetalert2.all.min.js"></script>
    <script src="../dashJs/jquery.min.js"></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>


    <!-- Preloader start -->


    <!-- Preloader end -->



    <!-- Main wrapper start -->

    <div id="main-wrapper">


        <!-- Nav header start -->

        <div class="nav-header">
            <div class="d-flex justify-content-center align-items-center " style="height: 100%;">
                <button id="sidebarToggle" style="background:none; border:none; font-size:24px; cursor:pointer;">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Nav header end -->


        <!-- Header start -->
        <div class="header" style="background: linear-gradient(90deg,rgb(0, 75, 236) 0%,rgb(103, 143, 252) 100%); box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <div class="header-content d-flex align-items-center justify-content-between px-4 py-2">
                <div class="d-flex align-items-center">
                    <!-- <div class="nav-control mr-3">
                        <div class="hamburger">
                            <span class="toggle-icon"><i class="fas fa-bars"></i></span>
                        </div>
                    </div> -->
                    <span class="brand-title d-flex align-items-center">
                        <img src="../images/logo.jpg" alt="logo" style="width: 40px; border-radius: 8px; margin-right: 10px;">
                        <h4 class="mb-0" style="font-weight: 600; color:white;">S&H အမျိုးသမီးသီးသန့် အလှပြုပြင်ရေး</h4>
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="input-group icons mr-3" style="max-width: 250px;">
                        <input type="search" class="form-control border-0 shadow-none" placeholder="ရှာရန်" aria-label="Search Dashboard" style="background: #f5f6fa; border-radius: 20px;">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent border-0"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= $_SESSION['image'] ?>" height="40" width="40" class="rounded-circle mr-2" alt="">
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

        <!-- Header end ti-comment-alt -->


        <!-- Sidebar start -->
        <div class="nk-sidebar">
            <div class="nk-nav-scroll">
                <ul class="metismenu">
                    <?php
                    if ($_SESSION['role'] == "staff") { ?>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="arrow-right"></i><span class="nav-text">ဝန်ထမ်းတာဝန်များ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../staff/task_list.php">စာရင်း</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="arrow-right"></i><i class="fa-solid fa-user"></i><span class="nav-text">အသုံးပြုသူများ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/user_list.php">စာရင်း</a></li>
                                <li><a href="../admin/user_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="arrow-right"></i><i class="fa-solid fa-circle-user"></i><span class="nav-text">ဖောက်သည်</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/customer_list.php">စာရင်း</a></li>
                                <li><a href="../admin/customer_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class=""></i><i class="fa-solid fa-bell-concierge"></i><span class="nav-text">ဝန်ဆောင်မှုများ</span>
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
                                <i class=""></i><i class="fa-regular fa-calendar-check"></i><span class="nav-text">အချိန်ချိန်းဆိုမှုစာရင်း</span>
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

        <script>
            $(document).ready(function() {
                $('#sidebarToggle').on('click', function() {
                    $('.nk-sidebar').toggle();
                });
            });
        </script>