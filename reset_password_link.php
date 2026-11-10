<?php
session_start();
include 'db.php';
$msg = "";
$valid_token = false;

if(isset($_GET['token'])){
    $token = mysqli_real_escape_string($con, $_GET['token']);
    
    $sql = "SELECT * FROM password_resets WHERE token='$token' AND expires_at > NOW()";
    $result = mysqli_query($con, $sql);
    
    if(mysqli_num_rows($result) == 1){
        $valid_token = true;
        $reset_data = mysqli_fetch_assoc($result);
        $email = $reset_data['email'];
    } else {
        $msg = "<div class='alert alert-danger'>Invalid or expired token! <a href='forgot_password.php'>Request new link</a></div>";
    }
} else {
    // IDHA ADD PANNU - Token illana message kaatum
    $msg = "<div class='alert alert-warning'>Password reset link invalid. <a href='forgot_password.php'>Click here</a> to request a new link.</div>";
}

// Password update
if(isset($_POST['update_password']) && $valid_token){
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    
    if($new_pass != $confirm_pass){
        $msg = "<div class='alert alert-danger'>Passwords don't match!</div>";
    } elseif(strlen($new_pass) < 6){
        $msg = "<div class='alert alert-danger'>Password must be at least 6 characters!</div>";
    } else {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        
        // Update password in users table
        $update = mysqli_query($con, "UPDATE users SET password='$hashed' WHERE email='$email'");
        
        if($update){
            // Token delete pannu
            mysqli_query($con, "DELETE FROM password_resets WHERE email='$email'");
            $msg = "<div class='alert alert-success'>Password updated successfully! <a href='login.php'>Login Now</a></div>";
            $valid_token = false; // Form hide pannu
        } else {
            $msg = "<div class='alert alert-danger'>Error updating password</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h4><i class="fas fa-key"></i> Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <?php echo $msg; ?>
                        
                        <?php if($valid_token){ ?>
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control" required minlength="6">
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>
                            
                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required minlength="6">
                            </div>
                            
                            <button type="submit" name="update_password" class="btn btn-success w-100">
                                Update Password
                            </button>
                        </form>
                        <?php } ?>
                        
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