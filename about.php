<?php
require './template_layout/header.php';
require './require/db.php';
require "./require/common.php";
require './require/common_function.php';
// require '../require/check_auth.php'

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
    <div class="row d-flex">
      <div class="col-md-6 d-flex ftco-animate">
        <div class="img img-about align-self-stretch" style="background-image: url(images/bg_3.jpg); width: 100%;"></div>
      </div>
      <div class="col-md-6 pl-md-5 ftco-animate">
        <h2 class="mb-4">S&H အလှပြင်ဆိုင်ဝက်ဘ်ဆိုက်မှ ကြိုဆိုပါတယ်</h2>
        <p>ကျွန်မတို့၏အလှပြင်ဆိုင်တွင် လူတိုင်းအလှပျော်ရွှင်မှု၊ ယုံကြည်မှုနှင့်အတွင်းပိုင်းအားကောင်းမှုကိုခံစားနိုင်စေရန် ကြိုးစားအားထုတ်နေပါသည်။ အတွေ့အကြုံပြည့်ဝသော ပရော်ဖက်ရှင်နယ်အဖွဲ့၊ အရည်အသွေးကောင်းမွန်မှုအပေါ်စိတ်တဇပြင်းပြမှုနှင့် အလှအပအနုပညာကိုချစ်မြတ်နိုးသောစိတ်ဖြင့် သင့်ရဲ့သဘာဝလှပမှုကို မြှင့်တင်ပေးနိုင်ပြီး သင့်ပုဂ္ဂိုလ်ရေးအမှတ်အသားနှင့် ကိုက်ညီသော ဝန်ဆောင်မှုများကို တစ်ဦးချင်းစီအတွက် ထူးခြားစွာပေးဆောင်နေပါသည်။</p>
      </div>
    </div>
  </div>
</section>

<section class="ftco-section" id="ourbeauty-experts">
  <div class="container">
    <div class="row justify-content-center mb-5 pb-3">
      <div class="col-md-7 heading-section ftco-animate text-center">
        <h2 class="mb-6">ကျွန်မတို့ဆိုင်၏ အလှပြင်ကျွမ်းကျင်သူများ</h2>
        <p class="mb-4 text-light">ကျွန်မတို့ဆိုင်၏ အလှပြင်ပညာရှင်များသည် သင့်သဘာဝလှပမှုကို ဖော်ထုတ်ပေးပြီး ယုံကြည်မှုကိုမြှင့်တင်နိုင်သော တစ်ဦးချင်းစီအတွက် အထူးပြုအလှကုထုံးများကို ပေးဆောင်ရာတွင် ကျွမ်းကျင်မှုရှိသော ပညာရှင်များဖြစ်ပါသည်။</p>
      </div>
    </div>
    <div class="row">
      <?php
      if ($user_res->num_rows > 0) {
        while ($data = $user_res->fetch_assoc()) { ?>
          <div class="col-md-3 ftco-animate">
            <div class="staff">
              <div class="img mb-4" style="background-image: url(./uplode/<?= $data['image'] ?>);"></div>
              <div class="info text-center">

                <h3><a href="teacher-single.html"></a><?= $data['name'] ?></h3>
                <strong class="position mb-4 text-dark"><?= $data['role'] ?></strong>
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