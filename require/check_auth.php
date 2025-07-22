<?php
session_start();

// Redirect to login if not authenticated
function checkAuth($requiredRole)
{
    // Check if user is logged in
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("Location: http://localhost/Beauty/login.php");
        exit();
    }

    // Check if session has expired (30 minutes inactivity)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        header("Location: http://localhost/Beauty/login.php?error=session_expired");
        exit();
    }

    $_SESSION['last_activity'] = time();
    if ($requiredRole && (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole)) {
        header("Location:  http://localhost/Beauty/" . $_SESSION['role'] . "/dashboard.php?unauthorized_msg=U dont't have access to this acc!");
        exit();
    }
}
