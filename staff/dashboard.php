<?php
require '../require/check_auth.php';
checkAuth('staff');
require '../layouts/header.php';
?>


<div class="content-body">

    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">

    </div>
    <!-- #/ container -->
</div>

<?php

require '../layouts/footer.php';

?>