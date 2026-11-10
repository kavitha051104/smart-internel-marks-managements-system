<?php
include 'session.php';
checkRole(['staff']); 
include 'db.php';
include 'header.php';

$staff_id = $_SESSION['user_id'];
$msg = '';

$subjects = mysqli_query($con, "SELECT subject_code, subject_name FROM subjects ORDER BY subject_name");

// Mark Entry Process
if(isset($_POST['submit'])){
    $subject_code = mysqli_real_escape_string($con, $_POST['subject_code']);
    $reg_no = mysqli_real_escape_string($con, $_POST['reg_no']);
    $student_name = mysqli_real_escape_string($con, $_POST['student_name']);
    $test1 = intval($_POST['test1']);
    $test2 = intval($_POST['test2']);
    $assignment = intval($_POST['assignment']);
    $seminar = intval($_POST['seminar']);
    
    $sub_query = mysqli_query($con, "SELECT subject_name FROM subjects WHERE subject_code='$subject_code'");
    $sub_data = mysqli_fetch_assoc($sub_query);
    $subject_name = $sub_data['subject_name'];
    
    // Validation
    if($test1 < 0 || $test1 > 50 || $test2 < 0 || $test2 > 50){
        $msg = "<div class='alert alert-danger'>Test marks 0 to 50 kulla irukanum!</div>";
    } elseif($assignment < 0 || $assignment > 5 || $seminar < 0 || $seminar > 5){
        $msg = "<div class='alert alert-danger'>Assignment & Seminar 0 to 5 kulla irukanum!</div>";
    } else {
        // Calculation: Best of 2 convert to 15
        $test1_15 = ($test1 / 50) * 15;
        $test2_15 = ($test2 / 50) * 15;
        $best_test = max($test1_15, $test2_15);
        $total_internal = $best_test + $assignment + $seminar;
        
        // Check if already exists
        $check = mysqli_query($con, "SELECT * FROM marks WHERE reg_no='$reg_no' AND subject_code='$subject_code'");
        
        if(mysqli_num_rows($check) > 0){
            // Update existing - staff_id removed
            $sql = "UPDATE marks SET 
                    student_name='$student_name',
                    subject_name='$subject_name',
                    test1='$test1',
                    test2='$test2',
                    assignment='$assignment',
                    seminar='$seminar',
                    test1_15='$test1_15',
                    test2_15='$test2_15',
                    best_test='$best_test',
                    total_internal='$total_internal'
                    WHERE reg_no='$reg_no' AND subject_code='$subject_code'";
            $action = "updated";
        } else {
            // Insert new - staff_id removed
            $sql = "INSERT INTO marks (reg_no, student_name, subject_code, subject_name, test1, test2, assignment, seminar, test1_15, test2_15, best_test, total_internal) 
                    VALUES ('$reg_no', '$student_name', '$subject_code', '$subject_name', '$test1', '$test2', '$assignment', '$seminar', '$test1_15', '$test2_15', '$best_test', '$total_internal')";
            $action = "inserted";
        }
        
        if(mysqli_query($con, $sql)){
            $msg = "<div class='alert alert-success'>Marks successfully $action! Total: ".number_format($total_internal, 2)."/25</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error: " . mysqli_error($con) . "</div>";
        }
    }
}
?>

<div class="container mt-4">
    <h2>Add Internal Marks</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Dashboard</a>
    <a href="view_all_marks.php" class="btn btn-secondary mb-3">view all marks</a>
    <?php echo $msg; ?>
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <b>Enter Student Internal Marks</b>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label><b>Select Subject</b></label>
                        <select name="subject_code" class="form-control" required>
                            <option value="">-- Select Your Subject --</option>
                            <?php 
                            $subjects_dropdown = mysqli_query($con, "SELECT subject_code, subject_name FROM subjects ORDER BY subject_name");
                            while($sub = mysqli_fetch_assoc($subjects_dropdown)){ 
                                echo "<option value='{$sub['subject_code']}'>{$sub['subject_code']} - {$sub['subject_name']}</option>";
                            } 
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label><b>Reg No</b></label>
                        <input type="text" name="reg_no" class="form-control" placeholder="Ex: 21CS001" required>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label><b>Student Name</b></label>
                        <input type="text" name="student_name" class="form-control" placeholder="Student Name" required>
                    </div>
                </div>
                
                <hr>
                <h5 class="text-primary">Test Marks (0-50)</h5>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label><b>Test 1 Mark</b></label>
                        <input type="number" name="test1" class="form-control" min="0" max="50" placeholder="0-50" required>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label><b>Test 2 Mark</b></label>
                        <input type="number" name="test2" class="form-control" min="0" max="50" placeholder="0-50" required>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label><b>Assignment</b></label>
                        <input type="number" name="assignment" class="form-control" min="0" max="5" placeholder="0-5" required>
                        <small class="text-muted">Max 5</small>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label><b>Seminar</b></label>
                        <input type="number" name="seminar" class="form-control" min="0" max="5" placeholder="0-5" required>
                        <small class="text-muted">Max 5</small>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <b>Calculation:</b> Best of 2 Tests (converted to /15) + Assignment (5) + Seminar (5) = Total /25<br>
                    <b>Pass Mark:</b> 12.5 / 25
                </div>
                
                <button type="submit" name="submit" class="btn btn-success btn-lg">
                    💾 Save Marks
                </button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>