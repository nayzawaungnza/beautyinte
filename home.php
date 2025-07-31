<?php
require './template_layout/header.php';
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';

$user_res = selectData("users", $mysqli, "WHERE role != 'customer' AND role != 'admin'", "*", "ORDER BY role ASC");
$promotions_sql = "SELECT * FROM `promotions` WHERE start_date <= CURDATE() AND end_date >= CURDATE()";
$promotions = $mysqli->query($promotions_sql);
?>
<section class="ftco-section">
	<div class="container glass d-flex justify-content-around">
		<div class="card col-md-4 mb-3" style="background-color: pink;">
			<div class="card-body">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-cosmetics"></span></div>
					<div class="media-body">
						<h3 class="heading">လက်သည်း</h3>
						<p class="mb-4 text-dark">လက်သည်းအလှပြင်ဝန်ဆောင်မှုများဖြင့် လက်သည်းပုံစံကို ချောမွှတ်အလှဆင်ကာ သဘာဝအလှကို ပိုမိုတောက်ပအောင်ဖော်ထုတ်ပေးပါသည်။
							လက်သည်းအရောင်များ၊ ဒီဇိုင်းအနုပညာများနှင့် ဂျယ်လ်နည်းပညာ အသုံးပြုခြင်းဖြင့် စတိုင်ကျသောအလှတရားကို ဖန်တီးနိုင်ပါသည်။ ခန္ဓာကိုယ်အလှပြင်မှုတွင် လက်သည်းသည်လည်း အရေးပါသော အစိတ်အပိုင်းတစ်ခုဖြစ်သဖြင့် စနစ်တကျပြင်ဆင်ခြင်းသည် ယုံကြည်မှုကိုမြှင့်တင်ပေးပါသည်။</p>
					</div>
				</div>
			</div>
		</div>

		<div class="card col-md-4 mb-3" style="background-color: pink;">
			<div class="card-body">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-curl"></span></div>
					<div class="media-body">
						<h3 class="heading">ဆံပင်ပုံစံ</h3>
						<p class="mb-4 text-dark">အလှပြင်ဆိုင်အတွင်းရှိ ဆံပင်အလှပြင်ဝန်ဆောင်မှုတွင် ဖောက်သည်၏ ကိုယ်ရည်ကိုယ်သွေးနှင့် စိတ်ကြိုက်အလိုအတန်ပြုလုပ်နိုင်ရန်အတွက် ဆံပင်ဖြတ်ခြင်း၊ အရောင်ဆိုးခြင်းနှင့် ပုံစံဖန်တီးခြင်းများ ပါဝင်သည်။
							ပရော်ဖက်ရှင်နယ် တူးလ်များနှင့် နည်းပညာများကို အသုံးပြု၍ သပ်ရပ်လှပသော ပုံသဏ္ဌာန်ကို ဖန်တီးပေးခြင်းဖြစ်ပါသည်။</p>
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
				<p class="mb-4 text-light">ကျွန်ုပ်တို့၏ အလှအပအထူးကျွမ်းကျင်သူများသည် သင့်သဘာဝအလှကို မြှင့်တင်ပေးရန်နှင့် သင့်ယုံကြည်မှုကို တိုးတက်စေရန်အတွက် ကိုယ်ပိုင်လိုအပ်ချက်များနှင့် ကိုက်ညီသော အလှပြင်ဝန်ဆောင်မှုများကို ပေးစွမ်းနိုင်သော ကျွမ်းကျင်သော ပညာရှင်များဖြစ်ပါသည်။</p>
			</div>
		</div>
		<div class="row">
			<?php
			if ($user_res->num_rows > 0) {
				while ($data = $user_res->fetch_assoc()) { ?>
					<div class="col-md-3 ftco-animate mb-3">
						<div class="staff" style="border-radius: 15px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); transition: transform 0.3s; background: #fff;">
							<div class="img mb-4" style="background-image: url('./uplode/<?= $data['image'] ?>'); background-size: cover; background-position: center; height: 200px; border-bottom: 1px solid #eee;"></div>
							<div class="info text-center" style="padding: 15px 10px;">
								<h3 style="margin-bottom: 10px; font-size: 20px; color: #333; font-weight: bold;"> <a href="teacher-single.html" style="text-decoration: none; color: inherit;"><?= $data['name'] ?></a> </h3> <strong class="position mb-2" style="display: block; color: #6c757d; font-weight: 600; font-size: 14px;"><?= $data['role'] ?></strong>
								<div class="text mt-2" style="font-size: 14px; color: #555;">
									<p><?= $data['description'] ?></p>
								</div>
							</div>
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
				<h3 class="mb-4 text-dark">ပရိုမိုးရှင်းများ</h3>
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
								✨ Valid: <?= date('M d', strtotime($data['start_date'])) ?> – <?= date('M d', strtotime($data['end_date'])) ?>
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
					<p class="mb-2 text-dark" style="font-weight: 500;">⏰ တနင်္လာနေ့ မှ – တနင်္ဂနွေနေ့အထိ: <span class="text-primary">မနက် ၉း၀၀ မှ – ည ၉း၀၀ အထိ</span></p>

				</div>
			</div>
		</div>
</section>
<?php require_once './template_layout/footer.php' ?>