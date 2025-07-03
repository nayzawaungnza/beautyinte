<?php

require '../layouts/header.php';
$error = false;
$name = 
$appointment_date_err =
$appointment_time_err =
$status_err =
$request_err =
$customer_name = 
$service_name = 
$staff_name = 
$appointment_date = 
$appointment_time = 
$status = 
$comment = 
$request = 
$serid = '';
date_default_timezone_set('Asia/Yangon');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT customers.name FROM  `customers` where id = '$id'";

    $oldData = $mysqli->query($sql)->fetch_assoc();
    $name = $oldData['name'];
}
$sql = "SELECT services.name,services.id FROM  `services`";
$services = $mysqli->query($sql);

$res = "SELECT * FROM  `users`";
$users = $mysqli->query($res);

if (isset($_POST['app_date']) && isset($_POST['btn_submit'])) {
    $cName = $_POST['customer_id'];
    $serid = $_POST['services'];
    $sttid = $_POST['staff_id'];
    $appointment_date = $_POST['app_date'];
    $appointment_time = $_POST['app_time'];
    $status = $_POST['status'];
    $comment = $_POST['comment'];
    $request = $_POST['request'];
    $today = date('Y-m-d');
    $current_time = date('H:i:s');
    if ($appointment_date < $today) {
        $appointment_date_err = "Appointment date must not be in the past.";
        $error = true;
    }

    if (empty($appointment_date)) {
        $error = true;
        $appointment_date_err = "Please add appointment date";
    }

    if (empty($appointment_time)) {
        $error = true;
        $appointment_time_err = "Please add appointment time";
    }

    if ($appointment_time <= $current_time) {
        $error = true;
        $appointment_time_err = "unavailable appointment time";
    }

    if (empty($status)) {
        $error = true;
        $status_err = "Please select status";
    }

    if (!$error) {
        foreach ($serid as $ser) {
            $sql = "INSERT INTO `appointments`(`customer_id`, `service_id`, `staff_id`, `appointment_date`, `appointment_time`, `status`, `comment`, `request`)
            VALUES ('$cName','$ser','$sttid','$appointment_date','$appointment_time','$status','$comment','$request')";
            $mysqli->query($sql);
        }
       
        echo "<script>window.location.href= 'http://localhost/Beauty/admin/appointment_list.php' </script>";
    }
}
?>

<!-- Content body start -->

<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">အလှပြင်ဆိုင် စနစ်အနှစ်ချုပ်မျက်နှာပြင်</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">ပင်မစာမျက်နှာ</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3>အချိန်ချိန်းဆိုမှုစာရင်း အသစ်ဖန်တီးပါ</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">ဖောက်သည်အမည်</label>
                        <input type="text" name="name" class="form-control" value="<?= $name ?>">
                        <input type="hidden" name="customer_id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">
                    </div>


                    <div class="form-group">
                        <label for="name" class="form-label">ဝန်ဆောင်မှု အမည်</label>
                        <?php
                        if ($services && $services->num_rows > 0) {
                            while ($row = $services->fetch_assoc()) {  ?>
                                <div class="form-check">
                <input class="form-check-input" type="checkbox" 
                       name="services[]" value="<?= $row['id'] ?>"
                       id="service<?= $row['id'] ?>">
                <label class="form-check-label" for="service<?= $row['id'] ?>">
                    <?= $row['name'] ?>
                </label>
            </div>
                            
                           <?php }
                        } 
                        ?>
                    </div>


                    <div class="form-group">
                        <label for="name" class="form-label">ဝန်ထမ်း အမည်</label>
                        <select name="staff_id" id="staff_id" class="form-control">
                            <option value="">‌ေရွးချယ်ရန် ဝန်ထမ်း</option>
                            <?php
                            if ($users && $users->num_rows > 0) {
                                while ($row = $users->fetch_assoc()) { ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php }
                            } else {
                                echo "<option value=''>No staff available</option>";
                            }
                            ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="name" class="form-label">ချိန်းဆိုသည့် ရက်စွဲ</label>
                        <input type="date" name="app_date" class="form-control" value="<?= $appointment_date ?>">
                        <small class="text-danger"><?= $appointment_date_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">ချိန်းဆိုသည့် အချိန်</label>
                        <input type="time" name="app_time" class="form-control" value="<?= $appointment_time ?>">
                        <small class="text-danger"><?= $appointment_time_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">အခြေအနေ</label>
                        <br>
                       <select name="status" id="status" class="form-control">
                        <option value="0">Pending</option>
                        <option value="1">Complete</option>
                        <option value="2">Reject</option>
                       </select>
                        <small class="text-danger"><?= $status_err ?></small>
                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">မှတ်ချက်</label>
                        <input type="text" name="comment" class="form-control" value="<?= $comment ?>">

                    </div>
                    <div class="form-group">
                        <label for="name" class="form-label">တောင်းဆိုမှု</label>
                        <input type="text" name="request" class="form-control" value="<?= $request ?>">
                        <small class="text-danger"><?= $request_err ?></small>
                    </div>

                    <div class="my-2">
                        <button class="btn btn-primary" type="submit" name="btn_submit">တင်သွင်းပါ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>

<!-- Content body end -->



<?php

require '../layouts/footer.php';

?>