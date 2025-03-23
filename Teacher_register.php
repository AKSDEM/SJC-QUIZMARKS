<?php
include 'db.php';

// Define departments (Replace with your actual department names)
$departments = ["Zoology", "Sociology", "Political Science", "Physics", "Mathematics", "History", "English", "Education", "Economics", "Commerce", "Chemistry", "Business Administration", "Botany"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $department = trim($_POST['department']); // Get selected department
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate password match
    if ($password !== $confirm_password) {
        echo "<script>
                alert('❌ Passwords do not match!');
                window.location.href = 'Teacher_register.php';
              </script>";
        exit();
    }

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM teachers WHERE username = ? OR email = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "ss", $username, $email);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_fetch_assoc($checkResult)) {
        echo "<script>
                alert('❌ Username or Email already exists!');
                window.location.href = 'Teacher_register.php';
              </script>";
        exit();
    }
    mysqli_stmt_close($checkStmt);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert teacher data into database
    $query = "INSERT INTO teachers (username, email, department, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $department, $hashed_password);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('✅ Teacher registered successfully!');
                    window.location.href = 'Teacher_login.php';
                  </script>";
        } else {
            die("❌ Registration failed: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        die("❌ Database error: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(to right, #007bff, #6610f2);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .register-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            max-width: 420px;
            width: 100%;
        }
        .register-container h2 {
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
        .login-link {
            margin-top: 15px;
            font-size: 14px;
        }
        .login-link a {
            color: #007bff;
            font-weight: bold;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .logo {
            max-width: 80px;
            margin-bottom: 20px;
        }
        /* Responsive */
        @media (max-width: 480px) {
            .register-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <img src="INST LOGO.png" alt="Institution Logo" class="logo">
        <h2>Teacher Registration</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department:</label>
                <select name="department" class="form-control" required>
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?= htmlspecialchars($dept); ?>"><?= htmlspecialchars($dept); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div class="login-link">
            Already a teacher? <a href="Teacher_login.php">Login</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
