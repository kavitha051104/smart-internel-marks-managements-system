<?php
include 'session.php';
checkRole(['admin']);
include 'db.php';
include 'header.php';

$msg = "";

if(isset($_POST['add_student'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    
    // DOB ah ve password ah use pannu
    $password = $dob; // 2000-01-15
   
    $check = mysqli_query($con, "SELECT * FROM users WHERE email='$email' OR reg_no='$reg_no'");
    if(mysqli_num_rows($check) > 0){
        $msg = "<div class='alert alert-danger'>Email or Register Number already exists!</div>";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT); // DOB ah hash pannu
        
        $sql = "INSERT INTO users (name, email, password, role, reg_no, department, year, dob) 
                VALUES ('$name', '$email', '$hash', 'student', '$reg_no', '$department', '$year', '$dob')";
        
        if(mysqli_query($con, $sql)){
            $msg = "<div class='alert alert-success'>Student added successfully! DOB is the password.</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error: ".mysqli_error($con)."</div>";
        }
    }
}
?>


<div class="container mt-4">
    <h2><i class="fas fa-user-graduate"></i> Add New Student</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
    
    <?php echo $msg; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Student Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Register Number</label>
                        <input type="text" name="reg_no" class="form-control" placeholder="Ex: 21001234567" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Student intha password use panni login pannuvaanga</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
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
                    
                    <div class="col-md-4 mb-3">
                        <label>Year</label>
                        <select name="year" class="form-control" required>
                            <option value="">Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                </div>
                
                <button type="submit" name="add_student" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Student
                </button>
            </form>
        </div>
    </div>
    
    <hr class="mt-5">
    <h4>Existing Students List</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Reg No</th>
                <th>Name</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Dept</th>
                <th>Year</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = mysqli_query($con, "SELECT * FROM users WHERE role='student' ORDER BY reg_no ASC");
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $dob_display = (!empty($row['dob']) && $row['dob'] != '0000-00-00') 
                                   ? date('d-m-Y', strtotime($row['dob'])) 
                                   : 'Not Set';
                    
                    echo "<tr>
                            <td>{$row['reg_no']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['email']}</td>
                            <td>{$dob_display}</td>
                            <td>{$row['department']}</td>
                            <td>{$row['year']}</td>
                            <td>
                                <a href='edit_student.php?id={$row['id']}' class='btn btn-sm btn-primary me-1'>
                                    <i class='fas fa-edit'></i> Edit
                                </a>
                                <a href='delete_student.php?id={$row['id']}' class='btn btn-sm btn-danger' 
                                   onclick=\"return confirm('{$row['name']},are you sure to delete?')\">
                                    <i class='fas fa-trash'></i> Delete
                                </a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No students found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>