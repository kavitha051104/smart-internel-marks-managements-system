<?php
include 'session.php';
checkRole(['admin']); // Admin mattum than staff add panna mudiyum
include 'db.php';
include 'header.php';

$msg = "";

if(isset($_POST['add_staff'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $department = mysqli_real_escape_string($con, $_POST['department']);
    
    // 1. Email already iruka nu check pannu
    $check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $msg = "<div class='alert alert-danger'>Email already exists!</div>";
    } else {
        // 2. Password ah hash pannu
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. DB la insert pannu - role = 'staff'
        $sql = "INSERT INTO users (name, email, password, role, department) 
                VALUES ('$name', '$email', '$hash', 'staff', '$department')";
        
        if(mysqli_query($con, $sql)){
            $msg = "<div class='alert alert-success'>Staff added successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error: ".mysqli_error($con)."</div>";
        }
    }
}
?>

<div class="container mt-4">
    <h2>Add New Staff</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
    
    <?php echo $msg; ?>
    
    <form method="POST" action="">
        <div class="mb-3">
            <label>Staff Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            <small class="text-muted">Staff intha password use panni login pannuvanga</small>
        </div>
        
        <div class="mb-3">
            <label>Department</label>
            <select name="department" class="form-control" required>
                <option value="">Select Department</option>
                <option value="CSE">CSE</option>
                <option value="ECE">ECE</option>
                <option value="EEE">EEE</option>
                <option value="MECH">MECH</option>
                <option value="IT">IT</option>
            </select>
        </div>
        
        <button type="submit" name="add_staff" class="btn btn-primary">Add Staff</button>
    </form>
    
    <hr class="mt-5">
    <h4>Existing Staff List</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $i = 1;
        $result = mysqli_query($con, "SELECT * FROM users WHERE role='staff' ORDER BY id DESC");
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $name_safe = htmlspecialchars($row['name'], ENT_QUOTES);
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['department']}</td>
                        <td>
                            <a href='edit_staff.php?id={$row['id']}' class='btn btn-sm btn-outline-info me-1'>
                                <i class='fas fa-edit'></i> Edit
                            </a>
                            <a href='delete_staff.php?id={$row['id']}' class='btn btn-sm btn-outline-secondary' 
                               onclick=\"return confirm('Delete staff {$name_safe}?')\">
                                <i class='fas fa-trash'></i> Delete
                            </a>
                        </td>
                      </tr>";
                $i++;
            }
        } else {
            echo "<tr><td colspan='5' class='text-center'>No Staff Found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>