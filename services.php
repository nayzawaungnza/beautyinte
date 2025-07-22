<?php require_once './template_layout/header.php';
require "./require/common.php";
require './require/db.php';
require './require/common_function.php';


$service_res = selectData("services", $mysqli, "", "*");

?>
<section id="beauty-pricing" style="padding: 60px 0; background: linear-gradient(to right, #fdfbfb, #ebedee); font-family: 'Segoe UI', sans-serif;">
  <div class="container" style="max-width: 1140px; margin: auto;">
    <div style="text-align: center; margin-bottom: 50px;">
      <h2 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 15px;">အလှအပ စျေးနှုန်းများ</h2>
      <p style="font-size: 15px; color: #555;">လျှို့ဝှက်စရိတ်မရှိဘဲ အရည်အသွေးမြင့် ဝန်ဆောင်မှုများကို တန်ဖိုးကျသော စျေးနှုန်းဖြင့် ပေးဆောင်ခြင်းဖြင့် အလှတိုးတက်စေခြင်း။</p>
    </div>

    <div style="display: flex; flex-wrap: wrap; gap: 30px; justify-content: center;">
      <?php
      function getServiceIcon($name)
      {
        $name = strtolower($name);
        if (strpos($name, 'hair') !== false) return '💇‍♀️';
        if (strpos($name, 'makeup') !== false) return '💄';
        if (strpos($name, 'nail') !== false) return '💅';
        if (strpos($name, 'spa') !== false || strpos($name, 'massage') !== false) return '💆‍♀️';
        if (strpos($name, 'skin') !== false || strpos($name, 'facial') !== false) return '🌸';
        return '✨'; // default
      }

      if ($service_res && $service_res->num_rows > 0) {
        while ($data = $service_res->fetch_assoc()) {
          $icon = getServiceIcon($data['name']);
      ?>
          <div style="background-color: #ffffff; border-radius: 20px; padding: 20px 20px; width: 300px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); text-align: center; transition: transform 0.3s ease; cursor: pointer;"
            onmouseover="this.style.transform='translateY(-10px)'"
            onmouseout="this.style.transform='translateY(0)'">
            <div style="font-size: 2.2rem; margin-bottom: 10px;"><?= $icon ?></div>
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