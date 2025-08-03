<?php
require './template_layout/header.php';
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';

// Get all categories for dropdown
$categories = selectData("product_categories", $mysqli, "", "*");

// Handle category filter
$filter_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

$where = "";
if ($filter_category) {
	$where = "WHERE category_id = $filter_category";
}

// Fetch products with (or without) category filter
$product_res = $mysqli->query("SELECT * FROM products $where");
?>

<section class="ftco-section" id="hairstylist">
	<div class="container">
		<div class="row justify-content-center mb-4 pb-3">
			<div class="col-md-7 heading-section ftco-animate text-center">
				<h2 class="mb-4">ဆိုင်ရှိရောင်းရန် ပစ္စည်းများ</h2>
			</div>
		</div>

		<div class="text-left mb-3 w-50">
			<form method="GET" action="product.php" id="categoryForm">
				<select name="category_id" class="form-control"
					style="box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); border-radius: 10px; border: 1px solid #ccc; padding: 10px; font-size: 16px;"
					onchange="this.form.action='product.php#hairstylist'; this.form.submit();">
					<option value="">-- အမျိုးအစားရွေးပါ --</option>
					<?php while ($cat = $categories->fetch_assoc()) { ?>
						<option value="<?= $cat['id'] ?>" <?= ($filter_category == $cat['id']) ? 'selected' : '' ?>>
							<?= $cat['name'] ?>
						</option>
					<?php } ?>
				</select>
			</form>
		</div>

		<div class="row">
			<?php
			if ($product_res && $product_res->num_rows > 0) {
				while ($data = $product_res->fetch_assoc()) { ?>
					<div class="col-md-3 mb-3 ftco-animate">
						<div class="staff" style="background-color:antiquewhite;">
							<div class="img mb-4" style=" background-image: url(./uplode/<?= $data['img'] ?>); "></div>
							<div class="info text-center">
								<h3 style="font-weight: bold;"><a href="teacher-single.html"></a><?= $data['name'] ?></h3>
								<span class="position mb-4 text-dark" style="font-size:20px;"><?= $data['price'] ?> ကျပ်</span>
								<span class="position mb-4 text-dark" style="font-size:18px;"><?= $data['description'] ?></span>
							</div>
						</div>
					</div>
			<?php }
			} else {
				echo "<div class='col-md-12 text-center'><p>မည်သည့်ပစ္စည်းမျှ မတွေ့ပါ။</p></div>";
			}
			?>
		</div>
	</div>
</section>

<?php require_once './template_layout/footer.php' ?>