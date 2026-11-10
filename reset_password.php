<?php
include 'session.php';
checkRole(['admin']); // Admin mattum password reset panna mudiyum
include 'db.php';
include 'header.php';

$msg = "";

if(isset($_POST['reset'])){
    $user_type = $_POST['user_type']; // 'student' or 'staff'
    $user_id = mysqli_real_escape_string($con, $_POST['user_id']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);

    // Password hash pannu
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // users table la dhan ellarum irukanga
    $sql = "UPDATE users SET password='$hashed_password' WHERE id='$user_id' AND role='$user_type'";

    if(mysqli_query($con, $sql)){
        if(mysqli_affected_rows($con) > 0){
            $msg = "<div class='alert alert-success'>Password reset successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>User not found or already has same password</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>Error: ".mysqli_error($con)."</div>";
    }
}
?>

<div class="container mt-4">
    <h2><i class="fas fa-key"></i> Reset User Password</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back</a>

    <?php echo $msg;?>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Select User & Set New Password</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <label>User Type</label>
                    <select name="user_type" id="user_type" class="form-control" required onchange="loadUsers()">
                        <option value="">-- Select Type --</option>
                        <option value="student">Student</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Select User</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">-- First Select User Type --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>New Password</label>
                    <input type="text" name="new_password" class="form-control" placeholder="Enter new password" required minlength="6">
                    <small class="text-muted">Minimum 6 characters</small>
                </div>

                <button type="submit" name="reset" class="btn btn-danger" onclick="return confirm('Password reset panna confirm ah?')">
                    <i class="fas fa-sync"></i> Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// AJAX use pannama simple dropdown
function loadUsers() {
    var type = document.getElementById('user_type').value;
    var userSelect = document.getElementById('user_id');
    userSelect.innerHTML = '<option value="">Loading...</option>';

    if(type!= ''){
        // PHP la users ah fetch panni JS la podurom
        <?php
        $all_users = mysqli_query($con, "SELECT id, name, reg_no, role FROM users WHERE role IN ('student','staff') ORDER BY name");
        $users_array = [];
        while($u = mysqli_fetch_assoc($all_users)){
            $users_array[$u['role']][] = $u;
        }
        echo "var users = ". json_encode($users_array). ";";
       ?>

        userSelect.innerHTML = '<option value="">-- Select User --</option>';
        if(users[type]){
            users[type].forEach(function(user){
                var text = user.role == 'student'? user.reg_no + ' - ' + user.name : user.name;
                userSelect.innerHTML += '<option value="'+user.id+'">'+text+'</option>';
            });
        }
    }
}
</script>

<?php include 'footer.php';?>