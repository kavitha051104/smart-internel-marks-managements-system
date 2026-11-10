<?php
include 'session.php';
checkRole(['admin', 'staff']);
include 'db.php';

$id = $_GET['id'];
mysqli_query($con, "DELETE FROM marks WHERE id=$id");
header("Location: view_all_marks.php");
?>