<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Mark Register</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<!-- Navbar - Ellam page layum varum -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap"></i> Smart Mark Register</a>
    <div class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id'])){ ?>
            <span class="navbar-text me-3">
                Hi, <?php echo $_SESSION['name']; ?> | Role: <?php echo ucfirst($_SESSION['role']); ?>
            </span>
            <a class="btn btn-danger btn-sm" href="logout.php">Logout</a>
        <?php } ?>
    </div>
  </div>
</nav>
<!-- Page content starts here -->