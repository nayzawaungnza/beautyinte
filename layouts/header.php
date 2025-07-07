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
</head>

<body>


    <!-- Preloader start -->


    <!-- Preloader end -->



    <!-- Main wrapper start -->

    <div id="main-wrapper">


        <!-- Nav header start -->

        <div class="nav-header">
            <div class="brand-logo mb-3">
                <a href="./dashboard.php">
                    <span class="brand-title">
                        <img src="../images/logo.jpg" alt="logo" style="width: 50px; ">
                        <h3>S&H အလှပြင်ဆိုင်</h3>
                    </span>
                </a>
            </div>
        </div>

        <!-- Nav header end -->


        <!-- Header start -->
        <div class="header">
            <div class="header-content clearfix">

                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
                <div class="header-left">
                    <div class="input-group icons">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0 pr-2 pr-sm-3" id="basic-addon1"><i class="mdi mdi-magnify"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="ရှာရန်" aria-label="Search Dashboard">
                        <div class="drop-down   d-md-none">
                            <form action="#">
                                <input type="text" class="form-control" placeholder="ရှာရန်">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="header-right">
                    <ul class="clearfix">
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative" data-toggle="dropdown">
                                <span class="activity active"></span>
                                <img src="images/user/1.png" height="40" width="40" alt="">
                            </div>
                            <div class="drop-down dropdown-profile   dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="app-profile.html"><i class="icon-user"></i> <span>ပရိုဖိုင်</span></a>
                                        </li>
                                        <hr class="my-2">
                                        <li><a href="<?= '../logout.php' ?>"><i class="icon-key"></i> <span>အကောင့်ထွက်ရန်</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
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
                                <i class="arrow-right"></i><span class="nav-text">Task (For Staff)</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../staff/task_list.php">စာရင်း</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="arrow-right"></i><span class="nav-text">အသုံးပြုသူများ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/user_list.php">စာရင်း</a></li>
                                <li><a href="../admin/user_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class="arrow-right"></i><span class="nav-text">ဖောက်သည်</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/customer_list.php">စာရင်း</a></li>
                                <li><a href="../admin/customer_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class=""></i><span class="nav-text">ဝန်ဆောင်မှုများ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/service_list.php">စာရင်း</a></li>
                                <li><a href="../admin/service_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class=""></i><span class="nav-text">ရောင်းရန်ပစ္စည်းများ</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/product_list.php">စာရင်း</a></li>
                                <li><a href="../admin/product_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class=""></i><span class="nav-text">အချိန်ချိန်းဆိုမှုစာရင်း</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/appointment_list.php">စာရင်း</a></li>

                            </ul>
                        </li>
                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class=""></i><span class="nav-text">ငွေပေးချေမှု</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/payment_list.php">စာရင်း</a></li>
                                <li><a href="../admin/payment_create.php">ဖန်တီးမည်</a></li>
                            </ul>
                        </li>

                        <li class="sidebar-click">
                            <a class="has-arrow arrow">
                                <i class=""></i><span class="nav-text">Product Sales</span>
                            </a>
                            <ul aria-expanded="true" class="pannel" style="display: none;">
                                <li><a href="../admin/product_sale_list.php">Sale List</a></li>
                                <li><a href="../admin/product_sale_create.php">Create Sale</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <!-- Sidebar end -->