<?php
include 'session.php';
checkRole(['admin']); // Admin mattum delete pannanum
include 'db.php';

$id = intval($_GET['id']);

// Staff ah delete pannum munadi avan potta marks lam iruka nu check pannanum
// Illana foreign key error varum. So ipo direct delete

mysqli_query($con, "DELETE FROM users WHERE id=$id AND role='staff'");
header("Location: add_staff.php");
exit;
?>