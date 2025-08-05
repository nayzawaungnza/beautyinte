<?php
require_once './template_layout/header.php';
require "./require/common.php";
require './require/db.php';
require './require/common_function.php';

// Get all service categories for dropdown
$categories = selectData("service_categories", $mysqli, "", "*");

// Handle category filter
$filter_category = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

$where = "";
if ($filter_category) {
  $where = "WHERE category_id = $filter_category";
}

// Fetch services
$service_res = $mysqli->query("SELECT * FROM services $where");
?>

<section id="beauty-pricing" style="padding: 60px 0; background: linear-gradient(to right, #fdfbfb, #ebedee); font-family: 'Segoe UI', sans-serif;">
  <div class="container" style="max-width: 1140px; margin: auto;">
    <div style="text-align: center; margin-bottom: 30px;">
      <h5 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 15px;">ဝန်ဆောင်မှု အမျိုးအစားများ</h5>
    </div>

    <div class="text-left mb-4 w-50">
      <form method="GET" action="services.php" id="serviceForm">
        <select name="category_id" class="form-control"
          style="box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); border-radius: 10px; border: 1px solid #ccc; padding: 10px; font-size: 16px;"
          onchange="this.form.action='services.php#beauty-pricing'; this.form.submit();">
          <option value="">-- ဝန်ဆောင်မှု အမျိုးအစားရွေးချယ်ရန် --</option>
          <?php while ($cat = $categories->fetch_assoc()) { ?>
            <option value="<?= $cat['id'] ?>" <?= ($filter_category == $cat['id']) ? 'selected' : '' ?>>
              <?= $cat['name'] ?>
            </option>
          <?php } ?>
        </select>
      </form>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center;">
      <?php
      function getServiceIcon($name)
      {
        $name = strtolower($name);
        if (strpos($name, 'hair') !== false) return '💇‍♀️';
        if (strpos($name, 'nail') !== false) return '💅';
        return '✨'; // default
      }

      if ($service_res && $service_res->num_rows > 0) {
        while ($data = $service_res->fetch_assoc()) {
      ?>
          <div style="background-color:lightpink; border-radius: 20px; padding: 20px 20px; width: 300px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); text-align: center; transition: transform 0.3s ease; cursor: pointer;"
            onmouseover="this.style.transform='translateY(-10px)'"
            onmouseout="this.style.transform='translateY(0)'">

            <img class="img mb-4 img-fluid" style="width: 100%; height: 200px;" src="./uplode/<?= $data['image'] ? $data['image'] : 'default.png' ?>">
            <h3 style="font-size: 1.4rem; color: #e91e63; margin-bottom: 10px;"><?= htmlspecialchars($data['name']) ?></h3>
            <p style="font-size: 1.2rem; color: #2c3e50; font-weight: bold; margin-bottom: 10px;"><?= number_format($data['price']) ?> ကျပ်</p>
            <p style="font-size: 0.95rem; color: #777; line-height: 1.6;"><?= htmlspecialchars($data['description']) ?></p>
          </div>
      <?php
        }
      } else {
        echo '<p style="text-align:center; color: #999;">ဝန်ဆောင်မှု မတွေ့ရှိပါ။</p>';
      }
      ?>
    </div>
  </div>
</section>

<?php require_once './template_layout/footer.php' ?>