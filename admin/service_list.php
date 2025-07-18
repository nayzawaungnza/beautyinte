<?php
require '../require/check_auth.php';
checkAuth('admin');
require "../require/common_function.php";
require '../require/db.php';
require '../require/common.php';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
$res = selectData('services', $mysqli, "", "*", "ORDER BY created_at ASC");


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT * FROM `services`";
if ($search !== '') {
    $search_escaped = $mysqli->real_escape_string($search);
    $sql .= " WHERE name LIKE '%$search_escaped%'";
}
$res = $mysqli->query($sql);

$delete_id = isset($_GET['delete_id']) ?  $_GET['delete_id'] : '';
if ($delete_id !== '') {
    $res = deleteData('services', $mysqli, "id=$delete_id");
    if ($res) {
        $url = $admin_base_url . "service_list.php?success=Delete service Success";
        header("Location: $url");
    }
}
require '../layouts/header.php';
?>
<div class="content-body py-3">
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb-3">
            <h3>ဝန်ဆောင်မှုစာရင်း</h3>
            <div class="">
                <a href="<?= $admin_base_url . 'service_create.php' ?>" class="btn btn-primary">
                    ဝန်ဆောင်မှု အသစ်ဖန်တီးရန်
                </a>
            </div>
        </div>
        <div class="col-12 mb-3">
            <form method="GET" class="form-inline d-flex justify-content-end">
                <input type="text" name="search" class="form-control mr-2" placeholder="Search by name" value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <div class="row">
            <div class="col-md-4 offset-md-8 col-sm-6 offset-sm-6">
                <?php if ($success !== '') { ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php } ?>
                <?php if ($error !== '') { ?>
                    <div class="alert alert-danger">
                        <?= $error ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr class="text-center">
                                    <th class="">စဉ်</th>
                                    <th class="">အမည်</th>
                                    <th class="">စျေးနှုန်း</th>
                                    <th class="">အကြောင်းအရာ ဖော်ပြချက်</th>
                                    <th class="">လုပ်ဆောင်မှု</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($res->num_rows > 0) {
                                    $i = 1;
                                    while ($row = $res->fetch_assoc()) { ?>
                                        <tr class="text-center">
                                            <td><?= $i++ ?></td>
                                            <td><?= $row['name'] ?></td>
                                            <td class="text-right"><?= number_format($row['price']) ?> ကျပ်</td>
                                            <td><?= $row['description'] ?></td>


                                            <td>
                                                <a href="./service_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success edit_btn mx-2">ပြင်ဆင်ရန်</a>
                                                <button data-id=" <?= $row['id'] ?>" class="btn btn-sm btn-danger delete_btn">ဖျက်ရန်</button>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
</div>
<!--**********************************
            Content body end
        ***********************************-->
<script>
    $(document).ready(function() {
        $('.delete_btn').click(function() {
            const id = $(this).data('id')
            Swal.fire({
                title: 'ဖျက်မည်ဆိုတာသေချာပြီလား',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ဖျက်မည်',
                cancelButtonText: 'မဖျက်ပါ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'service_list.php?delete_id=' + id
                }
            });
        })
    })
</script>
<?php
require '../layouts/footer.php';
?>