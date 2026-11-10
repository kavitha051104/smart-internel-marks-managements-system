<?php
session_start();
include 'db.php';
$msg = "";

if(isset($_POST['send_link'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // User irukaanu check pannu
    $check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) == 1){
        $user = mysqli_fetch_assoc($check);
        
        // Token generate pannu
        $token = bin2hex(random_bytes(50));
        
        // Old token eduthudu
        mysqli_query($con, "DELETE FROM password_resets WHERE email='$email'");
        
        // New token insert - MySQL time use pannu
        $sql = "INSERT INTO password_resets (email, token, expires_at) 
                VALUES ('$email', '$token', DATE_ADD(NOW(), INTERVAL 1 HOUR))";
        
        if(mysqli_query($con, $sql)){
            // Reset link
            $reset_link = "http://localhost/Project/reset_password_link.php?token=$token";
            
            $msg = "<div class='alert alert-success'>
                    Password reset link generated!<br>
                    <strong>Link:</strong> <a href='$reset_link'>$reset_link</a><br>
                    <small>This link valid for 1 hour only</small>
                    </div>";
            
        } else {
            $msg = "<div class='alert alert-danger'>Error generating token</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Email not found in our system!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h4><i class="fas fa-lock"></i> Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $msg; ?>
                        
                        <p class="text-muted">Enter your registered email. We will send reset link.</p>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                            </div>
                            <button type="submit" name="send_link" class="btn btn-primary w-100">
                                Send Reset Link
                            </button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="login.php">← Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>