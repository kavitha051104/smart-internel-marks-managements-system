<?php
include 'session.php';
checkRole(['admin', 'staff', 'student']);
include 'db.php';
include 'header.php';

$user_role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$reg_no = $_SESSION['reg_no'] ?? '';

// Filter
$filter_sub = isset($_GET['subject']) ? mysqli_real_escape_string($con, $_GET['subject']) : '';

$where = [];
$title = "";

if($user_role == 'admin'){
    $title = "All Students Internal Marks - Admin View";
} elseif($user_role == 'staff'){
    $title = "All Students Internal Marks - Staff View";
    // Staff ku avanga subject mattum kaatradhuna indha line add pannu
    // $where[] = "m.staff_id = '$user_id'"; 
} elseif($user_role == 'student'){
    $title = "Your Internal Marks";
    $where[] = "m.reg_no = '$reg_no'";
}

if($filter_sub != ''){
    $where[] = "m.subject_name = '$filter_sub'";
}

$where_clause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT * FROM marks m $where_clause ORDER BY m.subject_name, m.reg_no";
$result = mysqli_query($con, $sql);
?>

<div class="container mt-4">
    <h2><i class="fas fa-clipboard-list"></i> <?php echo $title; ?></h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back</a>
    
    <?php if($user_role != 'student'){ ?>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row">
                <div class="col-md-6">
                    <label>Filter by Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="Subject name" value="<?php echo $filter_sub; ?>">
                </div>
                <div class="col-md-6">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="view_all_marks.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>

    <?php
    if(mysqli_num_rows($result) > 0){
        $current_subject = '';
        $table_open = false;
        
        while($row = mysqli_fetch_assoc($result)){
            
            // Subject maaruna pudhu table start pannu
            if($current_subject != $row['subject_name']){
                
                // Munnadi table open la irundha close pannu
                if($table_open){
                    echo "</tbody></table></div></div></div><br>";
                }
                
                $current_subject = $row['subject_name'];
                $table_open = true;
                
                // Pudhu subject ku table header
                echo "<div class='card mb-4'>
                        <div class='card-header bg-primary text-white'>
                            <h5><i class='fas fa-book'></i> Subject: {$current_subject}</h5>
                        </div>
                        <div class='card-body'>
                            <div class='table-responsive'>
                                <table class='table table-bordered table-striped table-sm'>
                                    <thead class='table-dark'>
                                        <tr>";
                                        
                if($user_role != 'student'){
                    echo "<th>Reg No</th><th>Student Name</th>";
                }
                echo "              <th>Test 1</th>
                                    <th>Test 1 (15)</th>
                                    <th>Test 2</th>
                                    <th>Test 2 (15)</th>
                                    <th>Best Test</th>
                                    <th>Assignment</th>
                                    <th>Seminar</th>
                                    <th>Total Internal</th>";
                if($user_role != 'student'){
                    echo "<th>Action</th>";
                }
                echo "          </tr>
                            </thead>
                            <tbody>";
            }
            
            // Table row data
            $total = $row['total_internal'];
            echo "<tr>";
            if($user_role != 'student'){
                echo "<td><strong>{$row['reg_no']}</strong></td>
                      <td>{$row['student_name']}</td>";
            }
            echo "<td>{$row['test1']}</td>
                  <td>{$row['test1_15']}</td>
                  <td>{$row['test2']}</td>
                  <td>{$row['test2_15']}</td>
                  <td><strong>{$row['best_test']}</strong></td>
                  <td>{$row['assignment']}</td>
                  <td>{$row['seminar']}</td>
                  <td><span class='badge bg-success'>{$total}</span></td>";
            
            if($user_role != 'student'){
                echo "<td>
                    <a href='edit_marks.php?id={$row['id']}' class='btn btn-sm btn-primary me-1'>
                        <i class='fas fa-edit'></i> Edit
                    </a>
                    <a href='delete_marks.php?id={$row['id']}' class='btn btn-sm btn-danger' 
                       onclick=\"return confirm('Are you sure you want to delete marks for {$row['student_name']}?')\">
                        <i class='fas fa-trash'></i> Delete
                    </a>
                </td>";
            }
            echo "</tr>";
        }
        
        // Kadasi table ah close pannu
        if($table_open){
            echo "</tbody></table></div></div></div>";
        }
        
    } else {
        echo "<div class='alert alert-warning'>No records found</div>";
    }
    ?>
</div>

<?php include 'footer.php'; ?>