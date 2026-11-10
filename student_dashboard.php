<?php
include 'session.php';
checkRole(['student']); // Student mattum
include 'db.php';
include 'header.php';

// Session la irundhu student details eduka
$reg_no = $_SESSION['reg_no'];
$name = $_SESSION['name'];
$department = $_SESSION['department'];
$year = $_SESSION['year'];

// Student oda total marks
$total_query = mysqli_query($con, "SELECT COUNT(*) as cnt FROM marks WHERE reg_no='$reg_no'");
$total_subjects = mysqli_fetch_assoc($total_query)['cnt'];

// Pass count - 12.5 ku mela
$pass_query = mysqli_query($con, "SELECT COUNT(*) as cnt FROM marks WHERE reg_no='$reg_no' AND total_internal >= 12.5");
$pass_count = mysqli_fetch_assoc($pass_query)['cnt'];

// Average
$avg_query = mysqli_query($con, "SELECT AVG(total_internal) as avg_mark FROM marks WHERE reg_no='$reg_no'");
$avg_mark = mysqli_fetch_assoc($avg_query)['avg_mark'];
$avg_mark = $avg_mark ? number_format($avg_mark, 2) : 0;
?>

<div class="container mt-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-tachometer-alt"></i> My Dashboard</h2>
            <h5>Welcome, <span class="text-primary"><?= $name ?></span></h5>
            <p class="text-muted">
                <b>Reg No:</b> <?= $reg_no ?> | 
                <b>Dept:</b> <?= $department ?> | 
                <b>Year:</b> <?= $year ?>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-primary text-white shadow">
                <div class="card-body">
                    <h1><?= $total_subjects ?></h1>
                    <p class="mb-0">Total Subjects</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-success text-white shadow">
                <div class="card-body">
                    <h1><?= $pass_count ?></h1>
                    <p class="mb-0">Passed /25</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center bg-warning text-dark shadow">
                <div class="card-body">
                    <h1><?= $avg_mark ?></h1>
                    <p class="mb-0">Average /25</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My Marks Table -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> My Internal Marks</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Test 1 /10</th>
                            <th>Test 2 /10</th>
                            <th>Best /15</th>
                            <th>Assign /5</th>
                            <th>Seminar /5</th>
                            <th>Total /25</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        $marks = mysqli_query($con, "SELECT * FROM marks WHERE reg_no='$reg_no' ORDER BY subject_code ASC");
                        if(mysqli_num_rows($marks) > 0){
                            while($row = mysqli_fetch_assoc($marks)){ 
                                $pass = ($row['total_internal'] >= 12.5) ? 'Pass' : 'Fail';
                                $badge = ($row['total_internal'] >= 12.5) ? 'success' : 'danger';
                                $row_class = ($row['total_internal'] >= 12.5) ? '' : 'table-danger';
                        ?>
                        <tr class="<?= $row_class ?>">
                            <td><?= $i++ ?></td>
                            <td><b><?= $row['subject_code'] ?></b></td>
                            <td><?= $row['subject_name'] ?></td>
                            <td><?= $row['test1'] ?></td>
                            <td><?= $row['test2'] ?></td>
                            <td><b><?= number_format($row['best_test'], 2) ?></b></td>
                            <td><?= $row['assignment'] ?></td>
                            <td><?= $row['seminar'] ?></td>
                            <td><b class="text-primary"><?= number_format($row['total_internal'], 2) ?></b></td>
                            <td><span class="badge bg-<?= $badge ?>"><?= $pass ?></span></td>
                        </tr>
                        <?php } } else { ?>
                        <tr>
                            <td colspan="10" class="text-center text-danger py-4">
                                <i class="fas fa-exclamation-circle"></i> No marks uploaded yet. Contact your staff.
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            
        </div>
    </div>

    <!-- Pass/Fail Rule -->
    <div class="alert alert-info mt-4">
        <b><i class="fas fa-info-circle"></i> Note:</b> Pass mark is 12.5 out of 25 for Internal Assessment.
    </div>
</div>

<?php include 'footer.php'; ?>