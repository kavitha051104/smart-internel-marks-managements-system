<?php
include 'db.php';
include 'session.php';

// Admin illa na login ku anupidu
if($_SESSION['role'] != 'admin'){
    header("Location:login.php");
    exit();
}

// Dashboard count edukurathu
$total_students = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM users WHERE role='student'"))['count'];
$total_staff = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM users WHERE role='staff'"))['count'];
$total_subjects = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM subjects"))['count'];
$total_marks_entered = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM marks"))['count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Smart Mark Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .sidebar {
            height: 100vh;
            background: #2c3e50;
        }
        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 15px 20px;
        }
        .sidebar a:hover, .sidebar .active {
            background: #34495e;
            border-left: 4px solid #3498db;
        }
        .card-stat {
            border-left: 4px solid;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar p-0">
            <h4 class="text-white text-center py-4"><i class="bi bi-shield-lock"></i> Admin Panel</h4>
            <a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="add_staff.php"><i class="bi bi-person-plus"></i> Add Staff</a>
            <a href="add_student.php"><i class="bi bi-people"></i> Add Student</a>
            <a href="add_subject.php"><i class="bi bi-book"></i> Add Subject</a>
            <a href="assign_subject.php"><i class="bi bi-link-45deg"></i> Assign Subject</a>
            <a href="view_all_marks.php"><i class="bi bi-table"></i> View All Marks</a>
            <a href="reset_password.php"><i class="bi bi-key"></i> Reset Password</a>
            <a href="logout.php" class="text-danger">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Welcome, <?php echo $_SESSION['name'] ?? 'Admin'; ?> 👋</h2>
                <span class="badge bg-primary">Admin</span>
            </div>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card card-stat border-primary shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Students</h6>
                                    <h3><?php echo $total_students; ?></h3>
                                </div>
                                <div class="text-primary"><i class="bi bi-people-fill fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div> <!-- IDHA DHaN MISS PANNIRUKKA - Total Students card ku closing -->

                <div class="col-md-3">
                    <div class="card card-stat border-success shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Staff</h6>
                                    <h3><?php echo $total_staff; ?></h3>
                                </div>
                                <div class="text-success"><i class="bi bi-person-badge-fill fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-stat border-warning shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Total Subjects</h6>
                                    <h3><?php echo $total_subjects; ?></h3>
                                </div>
                                <div class="text-warning"><i class="bi bi-journal-bookmark-fill fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card card-stat border-info shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="text-muted">Marks Entered</h6>
                                    <h3><?php echo $total_marks_entered; ?></h3>
                                </div>
                                <div class="text-info"><i class="bi bi-clipboard-data-fill fs-1"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Staff & Students -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Recent Staff</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr><th>Name</th><th>Email</th></tr>
                                <?php
                                $staff_q = mysqli_query($con, "SELECT name, email FROM users WHERE role='staff' ORDER BY id DESC LIMIT 5");
                                if(mysqli_num_rows($staff_q) > 0){
                                    while($s = mysqli_fetch_assoc($staff_q)){
                                        echo "<tr><td>{$s['name']}</td><td>{$s['email']}</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2' class='text-center'>Staff illai</td></tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="bi bi-people"></i> Recent Students</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr><th>Reg No</th><th>Name</th><th>DOB</th></tr>
                                <?php
                                $stu_q = mysqli_query($con, "SELECT reg_no, name, dob FROM users WHERE role='student' ORDER BY id DESC LIMIT 5");
                                if(mysqli_num_rows($stu_q) > 0){
                                    while($st = mysqli_fetch_assoc($stu_q)){
                                        $regNo = $st['reg_no'] ?? '-';
                                        $dob = $st['dob'] ?? '-';
                                        echo "<tr><td>{$regNo}</td><td>{$st['name']}</td><td>{$dob}</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>Student illai</td></tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>