<?php
include 'db.php';

// Ellarum ku DOB ah password ah maathu
$result = mysqli_query($con, "SELECT id, dob FROM users WHERE role='student'");

while($row = mysqli_fetch_assoc($result)){
    $id = $row['id'];
    $dob = $row['dob']; // 2000-01-15
    
    if(!empty($dob) && $dob != '0000-00-00'){
        $hash = password_hash($dob, PASSWORD_DEFAULT);
        mysqli_query($con, "UPDATE users SET password='$hash' WHERE id='$id'");
        echo "Updated: ID $id <br>";
    }
}
echo "All done!";
?>