<?php
include 'session.php';
checkRole(['admin']);
include 'db.php';
include 'header.php';

$id = intval($_GET['id']);
$result = mysqli_query($con, "SELECT * FROM users WHERE id=$id AND role='staff'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $department = mysqli_real_escape_string($con, $_POST['department']);
    
    $update = "UPDATE users SET name='$name', email='$email', department='$department' WHERE id=$id";
    if(mysqli_query($con, $update)){
        echo "<script>alert('Staff updated!'); window.location='add_staff.php';</script>";
    }
}
?>

<div class="container mt-4">
    <h3>Edit Staff - <?php echo $row['name']; ?></h3>
    <form method="POST">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="<?php echo $row['department']; ?>">
        </div>
        <button type="submit" name="update" class="btn btn-success">Update Staff</button>
        <a href="add_staff.php" class="btn btn-secondary">Back</a>
    </form>
</div>
<?php include 'footer.php'; ?>