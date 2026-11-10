<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'] ?? '';
$user_email = $_SESSION['email'] ?? '';
$user_role = strtolower(trim($_SESSION['role'] ?? '')); // Inga dhan magic

function checkRole($allowed_roles = []) {
    $current_role = strtolower(trim($_SESSION['role'] ?? ''));
    $allowed_roles = array_map('strtolower', array_map('trim', $allowed_roles)); 
    
    if (!in_array($current_role, $allowed_roles)) {
        header("Location: access_denied.php");
        exit();
    }
}
?>