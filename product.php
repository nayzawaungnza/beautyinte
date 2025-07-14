<?php
require './template_layout/header.php';
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';

$product_res = selectData("products", $mysqli, "", "*");
?>

<section class="ftco-section" id="hairstylist">
	<div class="container">
		<div class="row justify-content-center mb-5 pb-3">
			<div class="col-md-7 heading-section ftco-animate text-center">
				<h2 class="mb-6">ဆိုင်ရှိရောင်းရန် ပစ္စည်းများ</h2>
			</div>
		</div>
		<div class="row">
			<?php
			if ($product_res->num_rows > 0) {
				while ($data = $product_res->fetch_assoc()) { ?>
					<div class="col-lg-3 d-flex mb-sm-4 ftco-animate">
						<div class="staff">
							<div class="img mb-4" style="background-image: url(images/beauty.jpg);"></div>
							<div class="info text-center">

								<h3><a href="teacher-single.html"></a><?= $data['name'] ?></h3>
								<span class="position mb-4 text-dark"><?= $data['price'] ?></span>
								<span class="position mb-4 text-dark"><?= $data['description'] ?></span>

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