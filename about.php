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
        <h4 class="mb-4">S&H အလှပြင်ဆိုင်ဝက်ဘ်ဆိုက်မှ ကြိုဆိုပါတယ်</h4>
        <p class="mb-4 text-dark bold">S&H အလှအပဝန်ဆောင်မှုအဖွဲ့သည်ခေတ်မီဖက်ရှင်နဲ့အညီ၊ သဘာဝကျကျ အလှတရားကိုဖော်ပြနိုင်အောင် ရည်ရွယ်၍ ဖန်တီးထားသော အလှပြင်စင်တာတစ်ခုဖြစ်ပါသည်။
          အရည်အသွေးမြင့် ဝန်ဆောင်မှုများ ဖြင့် ဖောက်သည်တိုင်းကို သက်တောင့်သက်သာနဲ့ စိတ်ချမ်းသာစေဖို့ ရည်ရွယ်ထားပါသည်။
          ဆံပင်အလှပြင်မှု၊ လက်သည်းပုံဖော်ခြင်း စသည့်ဝန်ဆောင်မှုများကိုလည်း ထုတ်လုပ်ပေးနေပါသည်။
          ဖောက်သည်စိတ်ကျေနပ်မှု ကို ဦးစားပေးပြီး၊ သန့်ရှင်းသပ်ရပ်မှု၊ ခေတ်မီနည်းစနစ်၊ တာဝန်ရှိမှုရှိသော ဝန်ထမ်းအဖွဲ့မှ တာဝန်ယူပြုလုပ်ပေးသည်။
          S&H ဆိုင်သည် ခေတ်မီအလှအပနဲ့ သဘာဝတရားကိုပေါင်းစပ်ဖော်ပြနိုင်တဲ့အတွက် မိမိအလှအပအတွက် ယုံကြည်စိတ်ချရသောနေရာတစ်ခုဖြစ်သည်။</p>
      </div>
    </div>
  </div>
</section>



<script>
  // Get all cards
  const cards = document.querySelectorAll('.card');

  // Add mouseover effects
  cards.forEach(card => {
    // Mouse enter effect
    card.addEventListener('mouseenter', () => {
      card.style.transform = 'translateY(-10px) scale(1.02)';
      card.style.boxShadow = '0px -15px 30px 0px rgba(0,0,0,0.2)';
    });

    // Mouse leave effect
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'translateY(0) scale(1)';
      if (card.id === 'mission-card') {
        card.style.boxShadow = '0px -10px 20px 0px hsla(0, 76%, 63%, 0.88)';
      } else {
        card.style.boxShadow = '0px -10px 20px 0px rgba(0,0,0,0.1)';
      }
    });
  });
</script>

<?php require_once './template_layout/footer.php' ?>