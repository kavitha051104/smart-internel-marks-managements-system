<?php

include 'session.php';
checkRole(['admin', 'staff']);
include 'db.php';
include 'header.php';

$id = $_GET['id'];
$sql = "SELECT * FROM marks WHERE id = $id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $test1 = $_POST['test1'];
    $test2 = $_POST['test2'];
    $assignment = $_POST['assignment'];
    $seminar = $_POST['seminar'];
    
    // Calculation
    $t1_15 = ($test1 / 50) * 15;
    $t2_15 = ($test2 / 50) * 15;
    $best = ($t1_15 + $t2_15) / 2; // Average
    $total = $best + $assignment + $seminar;
    
    $update = "UPDATE marks SET 
               test1='$test1', test1_15='$t1_15',
               test2='$test2', test2_15='$t2_15',
               best_test='$best', assignment='$assignment', 
               seminar='$seminar', total_internal='$total' 
               WHERE id=$id";
               
    if(mysqli_query($con, $update)){
        echo "<script>alert('Marks updated!'); window.location='view_all_marks.php';</script>";
    }
}
?>

<div class="container mt-4">
    <h3>Edit Marks - <?php echo $row['student_name']; ?></h3>
    <form method="POST">
        <div class="mb-3">
            <label>Subject: <?php echo $row['subject_name']; ?></label>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label>Test 1 (50)</label>
                <input type="number" name="test1" class="form-control" value="<?php echo $row['test1']; ?>" max="50">
            </div>
            <div class="col-md-3">
                <label>Test 2 (50)</label>
                <input type="number" name="test2" class="form-control" value="<?php echo $row['test2']; ?>" max="50">
            </div>
            <div class="col-md-3">
                <label>Assignment (5)</label>
                <input type="number" name="assignment" class="form-control" value="<?php echo $row['assignment']; ?>" max="5">
            </div>
            <div class="col-md-3">
                <label>Seminar (5)</label>
                <input type="number" name="seminar" class="form-control" value="<?php echo $row['seminar']; ?>" max="5">
            </div>
        </div>
        <button type="submit" name="update" class="btn btn-success mt-3">Update Marks</button>
        <a href="view_all_marks.php" class="btn btn-secondary mt-3">Back</a>
    </form>
</div>
<?php include 'footer.php'; ?>