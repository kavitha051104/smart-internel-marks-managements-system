<?php
include 'session.php';
checkRole(['admin']);
include 'db.php';
include 'header.php';

$id = intval($_GET['id']);
$result = mysqli_query($con, "SELECT * FROM users WHERE id='$id' AND role='student'");
if(mysqli_num_rows($result) == 0) die("Student not found!");
$student = mysqli_fetch_assoc($result);

$msg = "";
if(isset($_POST['update_student'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    $year = mysqli_real_escape_string($con, $_POST['year']);
    $dob = mysqli_real_escape_string($con, $_POST['dob']);
    
    $check = mysqli_query($con, "SELECT * FROM users WHERE (email='$email' OR reg_no='$reg_no') AND id != '$id'");
    if(mysqli_num_rows($check) > 0){
        $msg = "<div class='alert alert-danger'>Email or Reg No already used by another student!</div>";
    } else {
        $sql = "UPDATE users SET name='$name', email='$email', reg_no='$reg_no', department='$department', year='$year', dob='$dob' WHERE id='$id'";
        if(mysqli_query($con, $sql)){
            $msg = "<div class='alert alert-success'>Student updated!</div>";
            $student = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id='$id'"));
        }
    }
}
?>

<div class="container mt-4">
    <h2>Edit Student</h2>
    <a href="add_student.php" class="btn btn-secondary mb-3">← Back</a>
    <?= $msg ?>
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Student Name</label>
                        <input type="text" name="name" class="form-control" value="<?= $student['name'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Register Number</label>
                        <input type="text" name="reg_no" class="form-control" value="<?= $student['reg_no'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $student['email'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" class="form-control" value="<?= $student['dob'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Department</label>
                        <select name="department" class="form-control" required>
                            <option value="CSE" <?= $student['department']=='CSE'?'selected':'' ?>>CSE</option>
                            <option value="ECE" <?= $student['department']=='ECE'?'selected':'' ?>>ECE</option>
                            <option value="EEE" <?= $student['department']=='EEE'?'selected':'' ?>>EEE</option>
                            <option value="MECH" <?= $student['department']=='MECH'?'selected':'' ?>>MECH</option>
                            <option value="IT" <?= $student['department']=='IT'?'selected':'' ?>>IT</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Year</label>
                        <select name="year" class="form-control" required>
                            <option value="1" <?= $student['year']=='1'?'selected':'' ?>>1st Year</option>
                            <option value="2" <?= $student['year']=='2'?'selected':'' ?>>2nd Year</option>
                            <option value="3" <?= $student['year']=='3'?'selected':'' ?>>3rd Year</option>
                            <option value="4" <?= $student['year']=='4'?'selected':'' ?>>4th Year</option>
                        </select>
                    </div>
                </div>
                <button type="submit" name="update_student" class="btn btn-success">Update Student</button>
            </form>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>