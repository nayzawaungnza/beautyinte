<?php
require './template_layout/header.php';
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';

$user_res = selectData("users", $mysqli, "", "*", "ORDER BY role DESC");
// $new_user_sql = "SELECT 
//                     users.*, 
//                     users.name AS user_name, 
//                     FROM users
//                     ORDER BY users.id DESC";
// $new_user_res  = $mysqli->query($new_user_sql);
$service_res = selectData("services", $mysqli, "", "*");

?>
<section class="ftco-section">
	<div class="container glass">
		<div class="row">
			<div class="col-md-4 ftco-animate">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-facial-treatment"></span></div>
					<div class="media-body">
						<h3 class="heading">အသားအရေနှင့် အလှအပ ပြုစုမှု</h3>
						<p>အလှပြင်ဆိုင်သည် ချောမွှတ်နူးညံ့သော အလှပြင်ဝန်ဆောင်မှုများဖြစ်သော ဆံပင်ညှပ်ခြင်း၊ အသားအရေစောင့်ရှောက်မှု၊ မိတ်ကပ်လုပ်ခြင်းနှင့် လက်သည်းအလှပြင်ခြင်းတို့ကို ပေးဆောင်သောနေရာဖြစ်ပြီး လူတစ်ဦး၏ရုပ်ရည်အလှတရားကို တိုးတက်စေရန်ရည်ရွယ်သည်။</p>
					</div>
				</div>
			</div>
			<div class="col-md-4 ftco-animate">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-cosmetics"></span></div>
					<div class="media-body">
						<h3 class="heading">မိတ်ကပ်ပရို</h3>
						<p>မိတ်ကပ်ပညာသည် အလှပြင်ဆိုင်အတွင်းတွင် မိတ်ကပ်လုပ်ငန်းအတွေ့အကြုံရှိသူဖြစ်ပြီး၊ အခမ်းအနားများ၊ မင်္ဂလာဆောင်ပွဲများနှင့် ဓာတ်ပုံရိုက်ကွင်းများအတွက် အထူးကျွမ်းကျင်သော မိတ်ကပ်ဝန်ဆောင်မှုများပေးပါသည်။
							သူမတို့သည် ဖောက်သည်၏ မျက်နှာအလှအပကို အထူးပြုပြင်နိုင်သော နည်းစနစ်များနှင့် အရည်အသွေးမြင့် ထုတ်ကုန်များကို အသုံးပြုပြီး မြှင့်တင်ပြသပေးပါသည်။</p>
					</div>
				</div>
			</div>
			<div class="col-md-4 ftco-animate">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-curl"></span></div>
					<div class="media-body">
						<h3 class="heading">ဆံပင်ပုံစံ</h3>
						<p>အလှပြင်ဆိုင်အတွင်းရှိ ဆံပင်အလှပြင်ဝန်ဆောင်မှုတွင် ဖောက်သည်၏ ကိုယ်ရည်ကိုယ်သွေးနှင့် စိတ်ကြိုက်အလိုအတန်ပြုလုပ်နိုင်ရန်အတွက် ဆံပင်ဖြတ်ခြင်း၊ အရောင်ဆိုးခြင်းနှင့် ပုံစံဖန်တီးခြင်းများ ပါဝင်သည်။
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
					<div class="col-lg-3 d-flex mb-sm-4 ftco-animate">
						<div class="staff">
							<div class="img mb-4" style="background-image: url(images/beauty.jpg);"></div>
							<div class="info text-center">

								<h3><a href="teacher-single.html"></a><?= $data['name'] ?></h3>
								<span class="position mb-4 text-dark"><?= $data['role'] ?></span>
								<div class="text">
									<p>အကြိုဆိုဝန်ထမ်း သည် အဖွဲ့အစည်းတစ်ခု၏ ပထမဦးဆုံးအမြင်ကိုဖော်ပြသူဖြစ်ပြီး၊ ဧည့်သည်များအား နွေးထွေးစွာကြိုဆိုရန်နှင့် ဧည့်သည်များနှင့် ဝန်ထမ်းများအကြား ဆက်သွယ်ရေး ပြေပြစ်မှုရှိအောင် ထိန်းသိမ်းပေးရသောတာဝန်ရှိသူဖြစ်သည်။</p>
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

<section class="ftco-section" id="ourwork">
	<div class="container glass">
		<div class="row justify-content-center mb-5 pb-3">
			<div class="col-md-7 heading-section text-center ftco-animate">
				<h2 class="mb-4">ကျွန်ုပ်တို့၏ အလုပ်များ</h2>
				<p class="position mb-4 text-dark">ပရော်ဖက်ရှင်နယ် ဆံပင်အလှပြင်ခြင်း၊ ပြတ်သားလှပသော မိတ်ကပ်လုပ်ခြင်း၊ နှင့် စွဲမက်ဖွယ်အနီအဖြူဖြင့် နှုတ်ခမ်းအလှပြင်ခြင်းတို့အားဖြင့် သဘာဝအလှကို မြှင့်တင်ပေးခြင်းဖြစ်သည်။</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 ftco-animate">
				<a href="#" class="work-entry">
					<img src="images/work-2.jpg" class="img-fluid" alt="Colorlib Template">
					<div class="info d-flex align-items-center">
						<div>
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
								<span class="icon-search"></span>
							</div>
							<h3>ဆံပင်ပုံစံ</h3>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4 ftco-animate">
				<a href="#" class="work-entry">
					<img src="images/nail2.jpg" class="img-fluid" alt="Colorlib Template">
					<div class="info d-flex align-items-center">
						<div>
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
								<span class="icon-search"></span>
							</div>
							<h3>လက်သည်းပုံစံ</h3>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4 ftco-animate">
				<a href="#" class="work-entry">
					<img src="images/work-3.jpg" class="img-fluid" alt="Colorlib Template">
					<div class="info d-flex align-items-center">
						<div>
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
								<span class="icon-search"></span>
							</div>
							<h3>မိတ်ကပ်</h3>
						</div>
					</div>
				</a>
			</div>
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
				<h2 class="mb-4 text-dark">အလှအပ စျေးနှုန်းများ</h2>
				<p>လျှို့ဝှက်စရိတ်မရှိဘဲ အရည်အသွေးမြင့် ဝန်ဆောင်မှုများကို တန်ဖိုးကျသော စျေးနှုန်းဖြင့် ပေးဆောင်ခြင်းဖြင့် အလှတိုးတက်စေခြင်း။</p>
			</div>
		</div>
		<div class="row">
			<?php
			if ($service_res->num_rows > 0) {
				while ($data = $service_res->fetch_assoc()) { ?>
					<div class="col-md-3 ftco-animate">
						<div class="pricing-entry pb-5 text-center">
							<div>
								<h3><?= $data['name'] ?></h3>
							</div>
							<ul>

								<li><?= $data['price'] ?></li>
								<li><?= $data['description'] ?></li>

							</ul>

							<p class="button text-center"><a href="#"
									class="btn btn-primary btn-outline-primary px-4 py-3">မှာယူရန်</a></p>

						</div>
					</div>
			<?php
				}
			}
			?>
		</div>
	</div>
	</div>
	</div>

	</div>
	</div>
</section>

<section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url(images/bg_4.jpg);">
	<div class="overlay"></div>
	<div class="container glass">
		<div class="row justify-content-center">
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>မိတ်ကပ်ပြင်ဆင်မှုမှတ်တမ်းများ</span>
								<strong class="number" data-number="3500">0</strong>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>ထုတ်လုပ်သူ</span>
								<strong class="number" data-number="1000">0</strong>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>ဖောက်သည်များ၏ပျော်ရွှင်မှု</span>
								<strong class="number" data-number="3000">0</strong>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>အသားအရေကုသမှု</span>
								<strong class="number" data-number="900">0</strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php require_once './template_layout/footer.php' ?>