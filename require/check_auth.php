<?php
ob_start();
require '../require/common.php';
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

if (
    empty($_SESSION['name']) ||
    empty($_SESSION['email']) ||
    empty($_SESSION['role'])
) {
    if ($current_page !== 'login.php') {
        header("Location: {$admin_base_url}login.php");
        exit;
    }
} else {
    // var_dump($current_page);
    // // die();
    if ($current_page === 'login.php') {
        header("Location: {$admin_base_url}dashboard.php");
        exit;
    }
}
ob_end_flush();
