<?php
include 'db.php';

$pass = 'admin123';
$hash = password_hash($pass, PASSWORD_DEFAULT);

echo "Hash: " . $hash . "<br>";

// Direct ah DB la podu
mysqli_query($con, "DELETE FROM users WHERE email='admin@college.com'");
$sql = "INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@college.com', '$hash', 'admin')";

if(mysqli_query($con, $sql)){
    echo "✅ Done! Ippo login pannu<br>Email: admin@college.com<br>Password: admin123";
} else {
    echo "DB Error: " . mysqli_error($con);
}
?>