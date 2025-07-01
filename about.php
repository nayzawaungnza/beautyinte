
<?php 
require './template_layout/header.php';
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';
// require '../require/check_auth.php'

$user_res = selectData("users", $mysqli, "","*","ORDER BY role DESC");
// $new_user_sql = "SELECT 
//                     users.*, 
//                     users.name AS user_name, 
//                     FROM users
//                     ORDER BY users.id DESC";
// $new_user_res  = $mysqli->query($new_user_sql);
$service_res = selectData("services", $mysqli, "","*");

?>
<section class="ftco-section">
  <div class="container">
    <div class="row d-flex">
      <div class="col-md-6 d-flex ftco-animate">
        <div class="img img-about align-self-stretch" style="background-image: url(images/bg_3.jpg); width: 100%;"></div>
      </div>
      <div class="col-md-6 pl-md-5 ftco-animate">
        <h2 class="mb-4">Welcome to S&H Beauty Salon Website</h2>
        <p>At our beauty salon, we are passionate about helping every individual feel beautiful, confident, and empowered. With a team of experienced professionals, a commitment to quality, and a love for the art of beauty, we offer personalized services that enhance your natural elegance and reflect your unique style.</p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section" id="ourbeauty-experts">
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
			if($user_res->num_rows>0){
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



<?php require_once './template_layout/footer.php' ?>