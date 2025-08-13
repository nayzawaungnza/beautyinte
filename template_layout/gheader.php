<?php
session_start();
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <title>အလှပြင်ဆိုင်</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">


    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php if (basename($_SERVER['PHP_SELF']) == "product.php") { ?>
        <div class="hero-wrap js-fullheight" style="background-image: url('images/salon1.jpg');"
            data-stellar-background-ratio="0.5">
            <div class="overlay"></div>
            <div class="container">
                <div class="row no-gutters slider-text js-fullheight justify-content-center"
                    data-scrollax-parent="true">
                    <div class="col-md-8 mt-4 ftco-animate  text-center" data-scrollax=" properties: { translateY: '70%' }">
                        <div class="icon">
                            <a href="index.html" class="logo">
                                <span class="flaticon-flower"></span>
                                <h1 class="text-white font-extrabold">အလှပြင်ဆိုင် အချိန်ချိန်းဆိုမှုစနစ်</h1>
                            </a>
                        </div>
                        <h2 class="mb-4 text-light" data-scrollax="properties: { translateY: '30%', opacity: 1.6 }">S&H အမျိုးသမီးသီးသန့် အလှပြုပြင်ရေး</h2>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar sticky-top" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand  text-dark" style="font-size:20px; margin-left:5px;" href="/Beauty/home.php">S&H အမျိုးသမီးသီးသန့် အလှပြုပြင်ရေး</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="oi oi-menu"></span> Menu
            </button>

            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto" style="font-size:14px; font-weight:bold;">
                    <li class="nav-item"><a href="home.php" class="nav-link text-dark">ပင်မစာမျက်နှာ</a></li>
                    <li class="nav-item"><a href="product.php" class="nav-link text-dark">ရောင်းရန်ပစ္စည်းများ</a></li>
                    <li class="nav-item"><a href="about.php" class="nav-link  text-dark">အကြောင်းအရာ</a></li>
                    <li class="nav-item"><a href="services.php" class="nav-link text-dark">ဝန်ဆောင်မှုများ</a></li>
                    <li class="nav-item"><a href="contact.php" class="nav-link text-dark">ဆက်သွယ်ရန်</a></li>
                    <?php if (!isset($_SESSION['id'])) { ?>
                        <li class="nav-item"><a href="login.php" class="nav-link  text-dark">လော့ဂ်အင်ဝင်ရန်</a></li>
                        <li class="nav-item"><a href="register.php" class="nav-link  text-dark">အကောင့်ဖွင်ရန်</a></li>
                    <?php }  ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- END nav -->