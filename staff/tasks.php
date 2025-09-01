<?php
require '../require/check_auth.php';
checkAuth('admin');
require '../require/db.php';

if (isset($_GET['action'], $_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    if ($action === 'accept') $mysqli->query("UPDATE appointments SET status=3 WHERE id=$id");
    elseif ($action === 'reject') $mysqli->query("UPDATE appointments SET status=2 WHERE id=$id");
    elseif ($action === 'complete') $mysqli->query("UPDATE appointments SET status=1 WHERE id=$id");

    header('Location: task_list.php');
    exit;
}
