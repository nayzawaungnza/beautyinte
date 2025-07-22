<?php require_once './template_layout/header.php';
require "./require/common.php";
require './require/db.php';
require './require/common_function.php';


$service_res = selectData("services", $mysqli, "", "*");

?>



<!-- <section class="ftco-section"> -->
  <!-- <div class="container">
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
            <p>Mမိတ်ကပ်ပညာသည် အလှပြင်ဆိုင်အတွင်းတွင် မိတ်ကပ်လုပ်ငန်းအတွေ့အကြုံရှိသူဖြစ်ပြီး၊ အခမ်းအနားများ၊ မင်္ဂလာဆောင်ပွဲများနှင့် ဓာတ်ပုံရိုက်ကွင်းများအတွက် အထူးကျွမ်းကျင်သော မိတ်ကပ်ဝန်ဆောင်မှုများပေးပါသည်။ သူမတို့သည် ဖောက်သည်၏ မျက်နှာအလှအပကို အထူးပြုပြင်နိုင်သော နည်းစနစ်များနှင့် အရည်အသွေးမြင့် ထုတ်ကုန်များကို အသုံးပြုပြီး မြှင့်တင်ပြသပေးပါသည်။</p>
          </div>
        </div>
      </div>
      <div class="col-md-4 ftco-animate">
        <div class="media d-block text-center block-6 services">
          <div class="icon d-flex mb-3"><span class="flaticon-curl"></span></div>
          <div class="media-body">
            <h3 class="heading"></h3>
            <p>အလှပြင်ဆိုင်အတွင်းရှိ ဆံပင်အလှပြင်ဝန်ဆောင်မှုတွင် ဖောက်သည်၏ ကိုယ်ရည်ကိုယ်သွေးနှင့် စိတ်ကြိုက်အလိုအတန်ပြုလုပ်နိုင်ရန်အတွက် ဆံပင်ဖြတ်ခြင်း၊ အရောင်ဆိုးခြင်းနှင့် ပုံစံဖန်တီးခြင်းများ ပါဝင်သည်။ ပရော်ဖက်ရှင်နယ် တူးလ်များနှင့် နည်းပညာများကို အသုံးပြု၍ သပ်ရပ်လှပသော ပုံသဏ္ဌာန်ကို ဖန်တီးပေးခြင်းဖြစ်ပါသည်။</p>
          </div>
        </div>
      </div>
    </div>
  </div> -->

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
                  <li><?= $data['price'] ?>ကျပ်</li>
                  <li><?= $data['description'] ?></li>
                </ul>

                <p class="button text-center">
                  <a href="./user/appointment.php" class="btn btn-primary btn-outline-primary px-4 py-3">ဘိုကင်လုပ်ရန်</a>
                </p>
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



  <!-- <section class="ftco-section ftco-counter img" id="section-counter" style="background-image: url(images/bg_4.jpg);">
    <div class="overlay"></div>
    <div class="container">
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
  </section> -->

  <?php require_once './template_layout/footer.php' ?>