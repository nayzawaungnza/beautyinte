<?php
require './template_layout/header.php';
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';
// require '../require/check_auth.php'

$user_res = selectData("users", $mysqli, "WHERE role != 'customer' AND role != 'admin'", "*", "ORDER BY role ASC");
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
    <div class="row d-flex">
      <div class="col-md-6 d-flex ftco-animate">
        <div class="img img-about align-self-stretch" style="background-image: url(images/bg_3.jpg); width: 100%;"></div>
      </div>
      <div class="col-md-6 pl-md-5 ftco-animate">
        <h2 class="mb-4">S&H အလှပြင်ဆိုင်ဝက်ဘ်ဆိုက်မှ ကြိုဆိုပါတယ်</h2>
        <p class="mb-4 text-dark">S&H အလှအပဝန်ဆောင်မှုအဖွဲ့သည်ခေတ်မီဖက်ရှင်နဲ့အညီ၊ သဘာဝကျကျ အလှတရားကိုဖော်ပြနိုင်အောင် ရည်ရွယ်၍ ဖန်တီးထားသော အလှပြင်စင်တာတစ်ခုဖြစ်ပါသည်။
          အရည်အသွေးမြင့် ဝန်ဆောင်မှုများ ဖြင့် ဖောက်သည်တိုင်းကို သက်တောင့်သက်သာနဲ့ စိတ်ချမ်းသာစေဖို့ ရည်ရွယ်ထားပါသည်။
          ဆံပင်အလှပြင်မှု၊ လက်သည်းပုံဖော်ခြင်း စသည့်ဝန်ဆောင်မှုများကိုလည်း ထုတ်လုပ်ပေးနေပါသည်။
          ဖောက်သည်စိတ်ကျေနပ်မှု ကို ဦးစားပေးပြီး၊ သန့်ရှင်းသပ်ရပ်မှု၊ ခေတ်မီနည်းစနစ်၊ တာဝန်ရှိမှုရှိသော ဝန်ထမ်းအဖွဲ့မှ တာဝန်ယူပြုလုပ်ပေးသည်။
          S&H ဆိုင်သည် ခေတ်မီအလှအပနဲ့ သဘာဝတရားကိုပေါင်းစပ်ဖော်ပြနိုင်တဲ့ မိမိအလှအပအတွက် ယုံကြည်စိတ်ချရသောနေရာတစ်ခုဖြစ်စေလိုသည်။</p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section" id="ourbeauty-experts">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
        <h2 class="mb-6">အလှပြင်ကျွမ်းကျင်သူများ</h2>
        <p class="mb-4 text-light">S&H ဝန်ထမ်းအဖွဲ့သည် သင့်အလှအပကို ယုံကြည်စိတ်ချစွာအပ်လို့ရသော ပရော်ဖက်ရှင်နယ် လူမှုဝန်ထမ်းအဖွဲ့တစ်စုဖြစ်ပါသည်။ သူတို့၏ အတွေ့အကြုံ၊ ကျွမ်းကျင်မှုနှင့် ဖောက်သည်အပေါ်ပြုမူသော သဘောထားကောင်းမှုကြောင့် သင်၏အလှအပကို တန်ဖိုးရှိအောင် ဖန်တီးပေးနိုင်မည်ဖြစ်သည်။
      </div>
    </div>
    <div class="row">
      <?php
      if ($user_res->num_rows > 0) {
        while ($data = $user_res->fetch_assoc()) { ?>
          <div class="col-md-3 mb-3 ftco-animate">
            <div class="staff">
              <div class="img mb-4" style="background-image: url(./uplode/<?= $data['image'] ?>); background-size: cover; background-position: center; height: 200px; border-bottom: 1px solid #eee;"></div>
              <div class=" info text-center">

                <h3 style="margin-bottom: 10px; font-size: 20px; color: #333; font-weight: bold;"> <a href="teacher-single.html" style="text-decoration: none; color: inherit;"><?= $data['name'] ?></a> </h3>
                <h3 style="margin-bottom: 10px; font-size: 20px; color: #333; font-weight: bold;"> <a href="teacher-single.html" style="text-decoration: none; color: inherit;"><?= $data['position'] ?></a> </h3>
                <h3 style="margin-bottom: 10px; font-size: 20px; color: #333; font-weight: bold;"> <a href="teacher-single.html" style="text-decoration: none; color: inherit;"><?= $data['salary'] ?></a> ကျပ် </h3>

                <div class="text mt-1">
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



<?php require_once './template_layout/footer.php' ?>