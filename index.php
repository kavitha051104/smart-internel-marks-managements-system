<?php
session_start();
// User already login panniruntha dashboard ku anupidu
if(isset($_SESSION['user_id'])){
    if($_SESSION['role'] == 'admin'){
        header("Location: admin_dashboard.php");
    } elseif($_SESSION['role'] == 'staff'){
        header("Location:dashboard_staff.php");
    } elseif($_SESSION['role'] == 'student'){
        header("Location:view_marks.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Internal Mark Register - MCA Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #6be880 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
        }
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
        <i class="bi bi-journal-check"></i> Smart Mark Register
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#features">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#about">About</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light ms-2" href="login.php">
            <i class="bi bi-box-arrow-in-right"></i> Login
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3">Smart Internal Assessment System</h1>
        <p class="lead mb-4">Automate your internal marks calculation with Best of Two method. Paperless, Fast & Accurate.</p>
        <a href="login.php" class="btn btn-light btn-lg px-4 me-2">
            <i class="bi bi-box-arrow-in-right"></i> Login Now
        </a>
        <a href="login.php" class="btn btn-outline-light btn-lg px-4">
            <i class="bi bi-person-plus"></i> Student Register
        </a>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Key Features</h2>
        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card h-100 text-center p-4 border-0 shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h5>Admin Panel</h5>
                    <p>Manage staff, students, subjects. Assign subjects to staff and view all reports.</p>
                    <a href="login.php?role=admin" class="btn btn-sm btn-primary">Admin Login</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4 border-0 shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h5>Staff Dashboard</h5>
                    <p>Enter Test 1, Test 2, Assignment, Seminar marks. Auto calculate Best of Two out of 15.</p>
                    <a href="login.php?role=staff" class="btn btn-sm btn-success">Staff Login</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 text-center p-4 border-0 shadow-sm">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-eye"></i>
                    </div>
                    <h5>Student Portal</h5>
                    <p>Login with Reg No & DOB. View only your internal marks subject wise. 100% Secure.</p>
                    <a href="login.php?role=student" class="btn btn-sm btn-info">Student Login</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>About This Project</h2>
                <p class="lead">This is a web-based application developed for MCA Final Year Project.</p>
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle-fill text-success"></i> <b>Tech Stack:</b> PHP, MySQL, Bootstrap 5, WAMP Server</li>
                    <li><i class="bi bi-check-circle-fill text-success"></i> <b>Calculation:</b> Best of Two Internal Tests + Assignment + Seminar = 25 Marks</li>
                    <li><i class="bi bi-check-circle-fill text-success"></i> <b>Security:</b> Password Hashing, Session Management, SQL Injection Prevention</li>
                    <li><i class="bi bi-check-circle-fill text-success"></i> <b>Users:</b> Admin, Staff, Student with role based access</li>
                </ul>
            </div>
            <div class="col-md-6">
                <img src="https://placehold.co/600x400/667eea/white?text=Mark+Register+System" class="img-fluid rounded shadow" alt="Project Image">
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    <div class="container">
        <p class="mb-1">Smart Internal Mark Register © 2026 | MCA Final Year Project</p>
        <p class="mb-0">Developed using PHP & MySQL | <a href="login.php" class="text-white">Login</a></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>