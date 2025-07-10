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
	<div class="container">
		<div class="row">
			<div class="col-md-4 ftco-animate">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-facial-treatment"></span></div>
					<div class="media-body">
						<h3 class="heading">Skin &amp; Beauty Care</h3>
						<p>A beauty salon is a place where professional beauty treatments like hair styling, skincare, makeup, and nail care are
							provided to enhance a person's appearance.</p>
					</div>
				</div>
			</div>
			<div class="col-md-4 ftco-animate">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-cosmetics"></span></div>
					<div class="media-body">
						<h3 class="heading">Makeup Pro</h3>
						<p>Makeup Pro in a beauty salon provides professional makeup services for events, weddings, and photoshoots, enhancing
							clients’ facial features with expert techniques and high-quality products.</p>
					</div>
				</div>
			</div>
			<div class="col-md-4 ftco-animate">
				<div class="media d-block text-center block-6 services">
					<div class="icon d-flex mb-3"><span class="flaticon-curl"></span></div>
					<div class="media-body">
						<h3 class="heading">Hair Style</h3>
						<p>Hair styling in a beauty salon involves cutting, coloring, and designing hair to match the client’s personality and
							preferences, using professional tools and techniques to achieve a stylish and polished look.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section" id="hairstylist">
	<div class="container">
		<div class="row justify-content-center mb-5 pb-3">
			<div class="col-md-7 heading-section ftco-animate text-center">
				<h2 class="mb-6">Our Beauty Experts</h2>
				<p class="mb-4 text-light">Our beauty experts are skilled professionals dedicated to providing personalized beauty treatments that enhance your
					natural look and boost your confidence.</p>
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
									<p>Receptionist is the first impression of an organization, responsible for creating a welcoming environment and ensuring smooth communication between visitors and staff.</p>
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
	<div class="container">
		<div class="row justify-content-center mb-5 pb-3">
			<div class="col-md-7 heading-section text-center ftco-animate">
				<h2 class="mb-4">Our Work</h2>
				<p class="position mb-4 text-dark">Enhancing natural beauty through professional hair styling, flawless makeup, and stunning lip makeover.</p>
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
							<h3>Hair Style</h3>
						</div>
					</div>
				</a>
			</div>
			<div class="col-md-4 ftco-animate">
				<a href="#" class="work-entry">
					<img src="images/work-1.jpg" class="img-fluid" alt="Colorlib Template">
					<div class="info d-flex align-items-center">
						<div>
							<div class="icon mb-4 d-flex align-items-center justify-content-center">
								<span class="icon-search"></span>
							</div>
							<h3>Nail Style</h3>
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
							<h3>Makeup</h3>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</section>

<section class="ftco-partner bg-light">
	<div class="container">
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
	<div class="container">
		<div class="row justify-content-center mb-5 pb-3">
			<div class="col-md-7 heading-section text-center ftco-animate">
				<h2 class="mb-4 text-dark">Beauty Pricing</h2>
				<p>Enhancing beauty without hidden costs—quality services at fair prices.</p>
			</div>
		</div>
		<div class="row">
			<?php
			if ($service_res->num_rows > 0) {
				while ($data = $service_res->fetch_assoc()) { ?>
					<div class="col-md-3 ftco-animate">
						<div class="pricing-entry pb-5 text-center">
							<div>
								<h3 class="mb-4">Basic</h3>
								<span class="per">one trip</span>
							</div>

							<ul>

								<li><?= $data['name'] ?></li>
								<li><?= $data['price'] ?></li>
								<li><?= $data['description'] ?></li>

							</ul>

							<p class="button text-center"><a href="#"
									class="btn btn-primary btn-outline-primary px-4 py-3">Order now</a></p>

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
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-10">
				<div class="row">
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>Makeup Over Done</span>
								<strong class="number" data-number="3500">0</strong>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>Procedure</span>
								<strong class="number" data-number="1000">0</strong>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>Happy Client</span>
								<strong class="number" data-number="3000">0</strong>
							</div>
						</div>
					</div>
					<div class="col-md-6 col-lg-3 d-flex justify-content-center counter-wrap ftco-animate">
						<div class="block-18 text-center">
							<div class="text">
								<div class="icon"><span class="flaticon-flower"></span></div>
								<span>Skin Treatment</span>
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