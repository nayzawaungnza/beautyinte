<?php
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';

$user_res = selectData("users", $mysqli, "WHERE role != 'customer' AND role != 'admin'", "*", "ORDER BY role ASC");
$promotions_sql = "SELECT * FROM `promotions` WHERE start_date <= CURDATE() AND end_date >= CURDATE()";
$promotions = $mysqli->query($promotions_sql);
?>
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
					<li class="nav-item"><a href="about.php" class="nav-link  text-dark">အကြောင်းအရာ</a></li>
					<li class="nav-item"><a href="services.php" class="nav-link text-dark">ဝန်ဆောင်မှုများ</a></li>
					<?php if (!isset($_SESSION['id'])) { ?>
						<li class="nav-item"><a href="login.php" class="nav-link  text-dark">လော့ဂ်အင်ဝင်ရန်</a></li>
						<li class="nav-item"><a href="register.php" class="nav-link  text-dark">အကောင့်ဖွင်ရန်</a></li>
					<?php }  ?>
				</ul>
			</div>
		</div>
	</nav>

	<!-- END nav -->

	<section class="ftco-section">
		<div class="container glass d-flex justify-content-around">
			<div class="card col-md-4 mb-3" style="background-color: pink;">
				<div class="card-body">
					<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

					<div class="media d-block text-center block-6 services">
						<!-- <div class="icon d-flex mb-3"><span class="fa-solid fa-hand-sparkles"></span></div> -->
						<!-- <div class="icon d-flex justify-content-center align-items-center mb-3" style="width: 60px; height: 60px; background: #fce4ec; border-radius: 50%;">
						<i class="fas fa-hand-sparkles fa-2x" style="color: #e91e63;"></i>
					</div> -->
						<div class="media-body">
							<h3 class="heading">လက်သည်းပိုင်း</h3>
							<h3 class="text-justify text-dark" style="text-align: justify; text-justify: inter-word;">
								လက်သည်းအလှပြင်ခြင်းသည် မိမိတို့ရဲ့မျက်နှာပင်သာမကဘဲ တစ်ကိုယ်ရေးသန့်ရှင်းမှုနှင့် ကိုယ်တိုင်ဂရုစိုက်မှု (self-care) ကိုလည်း အထူးဖော်ပြနိုင်သော အစိတ်အပိုင်းတစ်ခုဖြစ်သည်။ လက်သည်းအရောင်များ၊ ဒီဇိုင်းအနုပညာများနှင့် ဂျယ်လ်နည်းပညာများကို အသုံးပြုခြင်းဖြင့် စတိုင်ကျသောအလှတရားကိုဖန်တီးနိုင်ပါသည်။ခန္ဓာကိုယ်အလှပြင်မှုတွင်လက်သည်းသည်လည်းအရေးပါသောအစိတ်အပိုင်းတစ်ခုဖြစ်သဖြင့်စနစ်တကျပြင်ဆင်ခြင်းသည် ယုံကြည်မှုကို မြှင့်တင်ပေးပါသည်။
							</h3>
						</div>
					</div>
				</div>
			</div>

			<div class="card col-md-4 mb-3" style="background-color: pink;">
				<div class="card-body">
					<div class="media d-block text-center block-6 services">
						<!-- <div class="icon d-flex mb-3"><span class="flaticon-curl"></span></div> -->
						<div class="media-body">
							<h3 class="heading">ဆံပင်ပုံစံ</h3>
							<h3 class="text-justify text-dark" style="text-align: justify; text-justify: inter-word;">
								ဆံပင်သည်လူတစ်ဦးချင်းစီ၏ပင်ကိုယ်ပုံစံနှင့်ယုံကြည်မှုကိုဖော်ပြသောအရေးကြီးဆုံးအပိုင်းဖြစ်သည်။ဆံပင်အမျိုးအစားနှင့်ဖောက်သည်တစ်ဦးချင်းစီ၏အသားအရေ၊မျက်နှာပုံစံနှင့်ကိုက်ညီအောင်အကြံဉာဏ်ပေးခြင်းဖြင့်ဝန်ဆောင်မှုပေးထားပါသည်။ပုံမှန်နေထိုင်မှုထဲကနေပင်ကိုယ်ပုံစံကိုထူးခြားအောင်ပြောင်းလဲဖို့S&Hဆံပင်ဆိုင်ကနေထိ‌ရောက်စွာပြောင်းလဲပေးနိုင်ပါသည်။ </h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>



	<section class="ftco-section" id="hairstylist">
		<div class="container glass">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section ftco-animate text-center">
					<h2 class="mb-6">အလှအပပညာရှင်များ</h2>
					<p class="mb-4 text-light">S&H ဝန်ထမ်းအဖွဲ့သည် သင့်အလှအပကို ယုံကြည်စိတ်ချစွာအပ်လို့ရသော ပရော်ဖက်ရှင်နယ် လူမှုဝန်ထမ်းအဖွဲ့တစ်စုဖြစ်ပါသည်။ သူတို့၏ အတွေ့အကြုံ၊ ကျွမ်းကျင်မှုနှင့် ဖောက်သည်အပေါ်ပြုမူသော သဘောထားကောင်းမှုကြောင့် သင်၏အလှအပကို တန်ဖိုးရှိအောင် ဖန်တီးပေးနိုင်မည်ဖြစ်သည်။</p>
				</div>
			</div>
			<div class="row">
				<?php
				if ($user_res->num_rows > 0) {
					while ($data = $user_res->fetch_assoc()) { ?>
						<div class="col-md-3 ftco-animate mb-3">
							<div class="staff" style="border-radius:10px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); transition: transform 0.3s; background: #fff;">

								<!-- Staff image -->
								<div class="img mb-4"
									style="background-image: url('./uplode/<?= htmlspecialchars($data['image']) ?>'); 
                               background-size: cover; 
                               background-position: center; 
                               height:200px; 
                               width:140px; 
                               border-bottom: 1px solid #eee;">
								</div>

								<!-- Button condition -->
								<?php
								if ($data['name'] === "ဇာဇာ") {
									echo '<a href="./staff/zarzar.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} elseif ($data['name'] === "ထက်ထက်") {
									echo '<a href="./staff/htethtet.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} elseif ($data['name'] === "နိုရာ") {
									echo '<a href="./staff/nora.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} elseif ($data['name'] === "နွေးနွေး") {
									echo '<a href="./staff/nway.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} elseif ($data['name'] === "နိုနို") {
									echo '<a href="./staff/nono.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} elseif ($data['name'] === "စုစု") {
									echo '<a href="./staff/susu.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} elseif ($data['name'] === "ဖြူဖြူ") {
									echo '<a href="./staff/phyu.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} elseif ($data['name'] === "အေးအေး") {
									echo '<a href="./staff/aye.php">
                                <button style="border-radius:15px;">ပိုမိုလေ့လာရန်</button>
                              </a>';
								} else {
									echo '<button style="border-radius:15px;" disabled>ပိုမိုလေ့လာရန်</button>';
								}
								?>

							</div>
						</div>
				<?php
					}
				}
				?>
			</div>


		</div>
	</section>

	<section class="ftco-partner bg-light">
		<div class="container glass">
			<div class="row partner justify-content-center">
				<div class="col-md-10">
					<div class="row">
						<div class="col-md-3 ftco-animate">
							<a href="#" class="partner-entry">
								<img src="images/partner-1.jpg" class="img-fluid" alt="Colorlib template">
							</a>
						</div>
						<div class="col-md-3 ftco-animate">
							<a href="#" class="partner-entry">
								<img src="images/partner-2.jpg" class="img-fluid" alt="Colorlib template">
							</a>
						</div>
						<div class="col-md-3 ftco-animate">
							<a href="#" class="partner-entry">
								<img src="images/partner-3.jpg" class="img-fluid" alt="Colorlib template">
							</a>
						</div>
						<div class="col-md-3 ftco-animate">
							<a href="#" class="partner-entry">
								<img src="images/partner-4.jpg" class="img-fluid" alt="Colorlib template">
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="ftco-section" id="beauty-pricing">
		<div class="container glass">
			<div class="row justify-content-center mb-5 pb-3">
				<div class="col-md-7 heading-section text-center ftco-animate">
					<h3 class="mb-4 text-dark">ပရိုမိုးရှင်း</h3>
					<!-- <p>လျှို့ဝှက်စရိတ်မရှိဘဲ အရည်အသွေးမြင့် ဝန်ဆောင်မှုများကို တန်ဖိုးကျသော စျေးနှုန်းဖြင့် ပေးဆောင်ခြင်းဖြင့် အလှတိုးတက်စေခြင်း။</p> -->
				</div>
			</div>
			<div class="row" style="display: flex; flex-wrap: wrap; gap: 25px; justify-content: center;">
				<?php
				if ($promotions && $promotions->num_rows > 0) {
					while ($data = $promotions->fetch_assoc()) { ?>
						<div class="col-md-6" style="flex: 0 0 calc(100% - 25px); max-width: 300px;">
							<div style="background: linear-gradient(to bottom right, #fceabb, #f8b500); border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); padding: 25px; text-align: center; transition: transform 0.3s; cursor: pointer;"
								onmouseover="this.style.transform='translateY(-10px)'"
								onmouseout="this.style.transform='translateY(0)'">
								<div style="font-size: 2rem; font-weight: bold; color: #d32f2f; margin-bottom: 10px;">
									<?= $data['percentage'] ?>% OFF
								</div>
								<h3 style="font-size: 1.3rem; font-weight: 600; color: #2c3e50; margin-bottom: 10px;">
									<?= htmlspecialchars($data['package_name']) ?>
								</h3>
								<p style="font-size: 0.95rem; color: #333; margin-bottom: 5px;">
									<?= htmlspecialchars($data['description']) ?>
								</p>
								<p style="font-size: 0.85rem; color: #555; margin: 10px 0; background-color: #fff3cd; padding: 5px 12px; border-radius: 15px; display: inline-block; font-weight: 500;">
									<?= date('M d', strtotime($data['start_date'])) ?> – <?= date('M d', strtotime($data['end_date'])) ?>
								</p>
							</div>
						</div>
				<?php }
				} ?>
			</div>


		</div>
		</div>
		</div>

		</div>
		</div>
	</section>

	<section class="ftco-section bg-white py-4" id="salon-hours">
		<div class="container glass">
			<div class="row justify-content-center mb-3">
				<div class="col-md-8 text-center">
					<div style="background: linear-gradient(to right, #f45995f6, #eadce0ff); padding: 20px; border-radius: 20px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); font-size: 1rem;">
						<h3 class="text-dark mb-3" style="font-weight: bold;">ဆိုင်ဖွင့်ချိန်</h3>
						<p class="mb-2 text-dark" style="font-weight: 500;">⏰ တနင်္လာနေ့ မှ – တနင်္ဂနွေနေ့အထိ : <span class="text-primary">မနက် ၉း၀၀ မှ – ည ၉း၀၀ အထိ</span></p>

					</div>
				</div>
			</div>
	</section>
</body>

</html>
<?php require_once './template_layout/footer.php' ?>