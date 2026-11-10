<?php
include 'session.php';
checkRole(['admin', 'staff']);
include 'db.php';
include 'header.php';

$msg = "";
$edit_mode = false;
$edit_data = [];

// Add Subject
if(isset($_POST['add_subject'])){
    $code = mysqli_real_escape_string($con, $_POST['subject_code']);
    $name = mysqli_real_escape_string($con, $_POST['subject_name']);
    
    $check = mysqli_query($con, "SELECT * FROM subjects WHERE subject_code='$code'");
    if(mysqli_num_rows($check) > 0){
        $msg = "<div class='alert alert-danger'>Subject Code already exists!</div>";
    } else {
        $sql = "INSERT INTO subjects (subject_code, subject_name) VALUES ('$code', '$name')";
        if(mysqli_query($con, $sql)){
            $msg = "<div class='alert alert-success'>Subject added successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error: ".mysqli_error($con)."</div>";
        }
    }
}

// Update Subject
if(isset($_POST['update_subject'])){
    $old_code = mysqli_real_escape_string($con, $_POST['old_code']);
    $new_code = mysqli_real_escape_string($con, $_POST['subject_code']);
    $name = mysqli_real_escape_string($con, $_POST['subject_name']);
    
    $sql = "UPDATE subjects SET subject_code='$new_code', subject_name='$name' WHERE subject_code='$old_code'";
    if(mysqli_query($con, $sql)){
        $msg = "<div class='alert alert-success'>Subject updated successfully!</div>";
        $edit_mode = false;
    } else {
        $msg = "<div class='alert alert-danger'>Error: ".mysqli_error($con)."</div>";
    }
}

// Delete Subject
if(isset($_GET['delete'])){
    $code = mysqli_real_escape_string($con, $_GET['delete']);
    $sql = "DELETE FROM subjects WHERE subject_code='$code'";
    if(mysqli_query($con, $sql)){
        $msg = "<div class='alert alert-success'>Subject deleted successfully!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Cannot delete. Used in marks table!</div>";
    }
}

// Edit Mode - Data fetch
if(isset($_GET['edit'])){
    $edit_mode = true;
    $code = mysqli_real_escape_string($con, $_GET['edit']);
    $res = mysqli_query($con, "SELECT * FROM subjects WHERE subject_code='$code'");
    $edit_data = mysqli_fetch_assoc($res);
}

// Get all subjects
$result = mysqli_query($con, "SELECT * FROM subjects ORDER BY subject_code");
?>

<div class="container mt-4">
    <h2><i class="fas fa-book"></i> Manage Subjects</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back</a>
    
    <?php echo $msg; ?>
    
    <div class="row">
        <!-- Add / Edit Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-<?php echo $edit_mode ? 'warning' : 'primary'; ?> text-white">
                    <h5><?php echo $edit_mode ? 'Edit Subject' : 'Add New Subject'; ?></h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <?php if($edit_mode){ ?>
                            <input type="hidden" name="old_code" value="<?php echo $edit_data['subject_code']; ?>">
                        <?php } ?>
                        
                        <div class="mb-3">
                            <label>Subject Code</label>
                            <input type="text" name="subject_code" class="form-control" 
                                   value="<?php echo $edit_mode ? $edit_data['subject_code'] : ''; ?>" 
                                   placeholder="Ex: CS101" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Subject Name</label>
                            <input type="text" name="subject_name" class="form-control" 
                                   value="<?php echo $edit_mode ? $edit_data['subject_name'] : ''; ?>" 
                                   placeholder="Ex: Data Structures" required>
                        </div>
                        
                        <?php if($edit_mode){ ?>
                            <button type="submit" name="update_subject" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update Subject
                            </button>
                            <a href="add_subject.php" class="btn btn-secondary">Cancel</a>
                        <?php } else { ?>
                            <button type="submit" name="add_subject" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add Subject
                            </button>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Subject List -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>Subject List - Total: <?php echo mysqli_num_rows($result); ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(mysqli_num_rows($result) > 0){
                                    $i = 1;
                                    while($row = mysqli_fetch_assoc($result)){
                                        echo "<tr>
                                            <td>{$i}</td>
                                            <td><strong>{$row['subject_code']}</strong></td>
                                            <td>{$row['subject_name']}</td>
                                            <td>
                                                <a href='add_subject.php?edit={$row['subject_code']}' class='btn btn-sm btn-warning'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </a>
                                                <a href='add_subject.php?delete={$row['subject_code']}' 
                                                   class='btn btn-sm btn-danger' 
                                                   onclick='return confirm(\"Subject {$row['subject_code']} ah delete pannava? Marks table la irundha delete aagathu!\")'>
                                                    <i class='fas fa-trash'></i> Delete
                                                </a>
                                            </td>
                                        </tr>";
                                        $i++;
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No subjects found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>