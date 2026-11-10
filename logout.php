<?php
// logout.php - Common for Admin, Staff, Student
session_start();

// Session clear pannu
$_SESSION = array();

// Session cookie delete pannu
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Cache clear - back button issue varakoodathu
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Ellarukum login.php ku anupu
header("Location: login.php?logout=success");
exit();
?>