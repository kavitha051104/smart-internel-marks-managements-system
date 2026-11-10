<?php
session_start();
include 'db.php'; // Database connection

$error = "";

// Already login panniruntha direct ah dashboard ku anupidu
if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: admin_dashboard.php");
    } elseif($_SESSION['role'] == 'staff'){
        header("Location: staff_dashboard.php");
    } elseif($_SESSION['role'] == 'student'){
        header("Location: student_dashboard.php"); // Idha maathiten
    }
    exit();
}

// Login button click pannuna
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($con, $_POST['username']); // Reg No or Email
    $password = $_POST['password']; // DOB for student, password for staff/admin
    $role = $_POST['role'];

    if($role == 'student'){
        // Student login: Reg No + DOB as password
        $sql = "SELECT * FROM users WHERE reg_no='$username' AND role='student'";
    } else {
        // Staff/Admin login: Email + Password
        $sql = "SELECT * FROM users WHERE email='$username' AND role='$role'";
    }
    
    $result = mysqli_query($con, $sql);
    
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        
        // Password verify pannu
        if(password_verify($password, $row['password'])){
            // Session set pannu
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['reg_no'] = $row['reg_no'];
            $_SESSION['department'] = $row['department']; // Idhu add pannu
            $_SESSION['year'] = $row['year']; // Idhu add pannu
            
            // Role base ah redirect
            if($role == 'admin'){
                header("Location: admin_dashboard.php");
            } elseif($role == 'staff'){
                header("Location: staff_dashboard.php");
            } elseif($role == 'student'){
                header("Location: student_dashboard.php"); // Idha maathiten
            }
            exit();
        } else {
            $error = "Invalid Password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Smart Mark Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            height: 100vh;
        }
        .login-box {
            margin-top: 100px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="login-box">
                <h3 class="text-center mb-4">Smart Mark Register Login</h3>
                
                <?php if($error != ""){ ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Select Role</label>
                        <select name="role" class="form-select" id="roleSelect" required>
                            <option value="">-- Select Role --</option>
                            <option value="student">Student</option>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" id="userLabel">Username</label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Enter Username" autocomplete="off" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" id="passLabel">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter Password" required>
                    </div>

                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    
                    <div class="text-center mt-3">
                       
                        <a href="forgot_password.php">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Role select pannuna placeholder maathura JS
document.getElementById('roleSelect').addEventListener('change', function() {
    var role = this.value;
    var userLabel = document.getElementById('userLabel');
    var passLabel = document.getElementById('passLabel');
    var username = document.getElementById('username');
    var password = document.getElementById('password');
    
    if(role == 'student'){
        userLabel.innerText = 'Register Number';
        username.placeholder = 'Enter Reg No ex: 22MCA01';
        passLabel.innerText = 'Date of Birth';
        password.placeholder = 'YYYY-MM-DD ex: 2000-01-15';
        password.type = 'date';
    } else if(role == 'staff' || role == 'admin'){
        userLabel.innerText = 'Email ID';
        username.placeholder = 'Enter Email';
        passLabel.innerText = 'Password';
        password.placeholder = 'Enter Password';
        password.type = 'password';
    }
});
</script>
</body>
</html>