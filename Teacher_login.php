<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_user = trim($_POST['teacher_user']);
    $teacher_pass = trim($_POST['teacher_pass']);

    // Secure query with prepared statements to prevent SQL injection
    $query = "SELECT * FROM teachers WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $teacher_user, $teacher_user);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $teacher = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($teacher && password_verify($teacher_pass, $teacher['password'])) {
        // Store session variables
        $_SESSION['teacher_logged_in'] = true;
        $_SESSION['teacher_id'] = $teacher['id'];
        $_SESSION['teacher_username'] = $teacher['username'];
        $_SESSION['teacher_department'] = $teacher['department']; // Store department for filtering

        header("Location: teacher_dashboard.php"); // Redirect to the dashboard
        exit();
    } else {
        echo "<script>
                alert('❌ Invalid credentials! Please try again.');
                setTimeout(() => { window.location.href = 'Teacher_login.php'; }, 2000);
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(to right, #007bff, #6610f2);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            background: #007bff;
            border: none;
            transition: 0.3s ease;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .register-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .register-link a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
        .logo {
            max-width: 80px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <img src="INST LOGO.png" alt="Institution Logo" class="logo">
        <h2>Teacher Login</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="teacher_user" class="form-label">Username or Email:</label>
                <input type="text" name="teacher_user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="teacher_pass" class="form-label">Password:</label>
                <input type="password" name="teacher_pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="Teacher_register.php">Register here</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
