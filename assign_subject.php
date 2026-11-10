<?php
include 'session.php';
checkRole(['admin']); 
include 'db.php';
include 'header.php';

$msg = "";

// Assign panna code - Prepared Statement oda
if(isset($_POST['assign'])){
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $subject_code = $_POST['subject_code'];
    $student_category = $_POST['student_category'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $academic_year = $_POST['academic_year'];
    
    // Already assign aagiruka nu check pannu
    $check_stmt = mysqli_prepare($con, "SELECT id FROM staff_subjects 
        WHERE staff_name=? AND subject_code=? AND academic_year=?
        AND department=? AND student_category=? AND year=? AND semester=?");
    mysqli_stmt_bind_param($check_stmt, "sssssii", $staff_name, $subject_code, $academic_year, $department, $student_category, $year, $semester);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    
    if(mysqli_stmt_num_rows($check_stmt) > 0){
        $msg = "<div class='alert alert-warning alert-dismissible fade show'><i class='fas fa-exclamation-triangle'></i> Already assigned for $department $student_category Year $year Sem $semester!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $insert_stmt = mysqli_prepare($con, "INSERT INTO staff_subjects (staff_name, staff_email, subject_code, student_category, department, year, semester, academic_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert_stmt, "sssssiis", $staff_name, $staff_email, $subject_code, $student_category, $department, $year, $semester, $academic_year);
        
        if(mysqli_stmt_execute($insert_stmt)){
            $msg = "<div class='alert alert-success alert-dismissible fade show'><i class='fas fa-check-circle'></i> Subject assigned successfully!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        } else {
            $msg = "<div class='alert alert-danger alert-dismissible fade show'><i class='fas fa-times-circle'></i> Error: ".mysqli_stmt_error($insert_stmt)."<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
        }
        mysqli_stmt_close($insert_stmt);
    }
    mysqli_stmt_close($check_stmt);
}

// Delete assign
if(isset($_GET['delete_id'])){
    $delete_id = $_GET['delete_id'];
    $del_stmt = mysqli_prepare($con, "DELETE FROM staff_subjects WHERE id=?");
    mysqli_stmt_bind_param($del_stmt, "i", $delete_id);
    mysqli_stmt_execute($del_stmt);
    mysqli_stmt_close($del_stmt);
    header("Location: assign_subject.php?deleted=1");
    exit();
}

if(isset($_GET['deleted'])){
    $msg = "<div class='alert alert-info alert-dismissible fade show'><i class='fas fa-trash'></i> Assignment removed successfully!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
}
?>

<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fas fa-link"></i> Assign Subject to Staff</h2>
        <a href="/Project/dashboard.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
    
    <?php echo $msg; ?>
    
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-gradient bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Assign New Subject</h5>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Select Staff</label>
                        <select name="staff_name" id="staff_select" class="form-select" required onchange="updateEmail()">
                            <option value="">-- Select Staff --</option>
                            <?php
                            $staff_query = mysqli_query($con, "SELECT name, email FROM users WHERE role='staff' ORDER BY name");
                            while($staff = mysqli_fetch_assoc($staff_query)){
                                echo "<option value='{$staff['name']}' data-email='{$staff['email']}'>{$staff['name']} - {$staff['email']}</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="staff_email" id="staff_email" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Select Subject</label>
                        <select name="subject_code" class="form-select" required>
                            <option value="">-- Select Subject --</option>
                            <?php
                            $subject_query = mysqli_query($con, "SELECT * FROM subjects ORDER BY subject_code");
                            while($subject = mysqli_fetch_assoc($subject_query)){
                                echo "<option value='{$subject['subject_code']}'>{$subject['subject_code']} - {$subject['subject_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Student Category</label>
                        <select name="student_category" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="UG">UG - Undergraduate</option>
                            <option value="PG">PG - Postgraduate</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Department</label>
                        <select name="department" class="form-select" required>
                            <option value="">-- Select Dept --</option>
                            <option value="CSE">CSE</option>
                            <option value="IT">IT</option>
                            <option value="ECE">ECE</option>
                            <option value="EEE">EEE</option>
                            <option value="MECH">MECH</option>
                            <option value="CIVIL">CIVIL</option>
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-semibold">Year</label>
                        <select name="year" class="form-select" required>
                            <option value="">-- Year --</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-semibold">Semester</label>
                        <select name="semester" class="form-select" required>
                            <option value="">-- Sem --</option>
                            <option value="1">Sem 1</option>
                            <option value="2">Sem 2</option>
                            <option value="3">Sem 3</option>
                            <option value="4">Sem 4</option>
                            <option value="5">Sem 5</option>
                            <option value="6">Sem 6</option>
                            <option value="7">Sem 7</option>
                            <option value="8">Sem 8</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2 mb-3">
                        <label class="form-label fw-semibold">Academic Year</label>
                        <select name="academic_year" class="form-select" required>
                            <option value="">Select</option>
                            <option value="2024-2025">2024-2025</option>
                            <option value="2025-2026" selected>2025-2026</option>
                            <option value="2026-2027">2026-2027</option>
                        </select>
                    </div>
                </div>
                
                <button type="submit" name="assign" class="btn btn-primary btn-lg px-4 mt-2">
                    <i class="fas fa-plus"></i> Assign Subject
                </button>
            </form>
        </div>
    </div>
    
    <h4 class="fw-bold mb-3"><i class="fas fa-table"></i> Current Assignments</h4>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Staff Name</th>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Category</th>
                            <th>Department</th>
                            <th class="text-center">Year/Sem</th>
                            <th>Academic Year</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($con, "SELECT ss.*, s.subject_name 
                                                      FROM staff_subjects ss
                                                      LEFT JOIN subjects s ON ss.subject_code = s.subject_code
                                                      ORDER BY ss.academic_year DESC, ss.department, ss.student_category, ss.year, ss.semester");
                        
                        if(mysqli_num_rows($result) > 0){
                            while($row = mysqli_fetch_assoc($result)){
                                $category_badge = $row['student_category'] == 'UG' ? 'bg-success' : 'bg-warning text-dark';
                                $subject_name = $row['subject_name'] ?? 'Subject Deleted';
                                echo "<tr>
                                        <td class='ps-4'>
                                            <div class='fw-semibold'>{$row['staff_name']}</div>
                                            <small class='text-muted'>{$row['staff_email']}</small>
                                        </td>
                                        <td><span class='badge bg-primary'>{$row['subject_code']}</span></td>
                                        <td>{$subject_name}</td>
                                        <td><span class='badge $category_badge'>{$row['student_category']}</span></td>
                                        <td><span class='badge bg-info text-dark'>{$row['department']}</span></td>
                                        <td class='text-center'>
                                            <span class='badge bg-secondary'>Y{$row['year']}</span>
                                            <span class='badge bg-secondary'>S{$row['semester']}</span>
                                        </td>
                                        <td><i class='fas fa-calendar-alt text-muted'></i> {$row['academic_year']}</td>
                                        <td class='text-center pe-4'>
                                            <a href='assign_subject.php?delete_id={$row['id']}' 
                                               class='btn btn-sm btn-danger' 
                                               onclick='return confirm(\"Remove this assignment?\")'>
                                               <i class='fas fa-trash'></i> Unassign
                                            </a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center py-5 text-muted'>
                                    <i class='fas fa-inbox fa-3x mb-3 d-block'></i>
                                    No assignments found
                                  </td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateEmail() {
    var select = document.getElementById('staff_select');
    var email = select.options[select.selectedIndex].getAttribute('data-email');
    document.getElementById('staff_email').value = email || '';
}

// Year select panna Sem auto filter aagum
document.addEventListener('DOMContentLoaded', function(){
    const yearSelect = document.querySelector('select[name="year"]');
    const semSelect = document.querySelector('select[name="semester"]');
    
    yearSelect.addEventListener('change', function(){
        const year = parseInt(this.value);
        semSelect.innerHTML = '<option value="">-- Sem --</option>';
        
        if(year >= 1 && year <= 4){
            const sem1 = (year * 2) - 1;
            const sem2 = year * 2;
            semSelect.innerHTML += `<option value="${sem1}">Sem ${sem1}</option>`;
            semSelect.innerHTML += `<option value="${sem2}">Sem ${sem2}</option>`;
        }
    });
});
</script>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.card { border-radius: 12px; }
.btn { border-radius: 8px; }
.table-hover tbody tr:hover { background-color: #f8f9fa; }
</style>

<?php include 'footer.php'; ?>