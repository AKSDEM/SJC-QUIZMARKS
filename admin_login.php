<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_user = $_POST['admin_user'];
    $admin_pass = $_POST['admin_pass'];

    // Fetch admin from database
    $query = "SELECT * FROM admins WHERE username = '$admin_user' OR email = '$admin_user'";
    $result = mysqli_query($conn, $query);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && password_verify($admin_pass, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>
                alert('❌ Invalid admin credentials!');
                setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);
              </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow" style="width: 300px;">
        <h3 class="text-center">Admin Login</h3>
        <form method="POST">
            <div class="mb-3">
                <label>Username:</label>
                <input type="text" name="admin_user" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="admin_pass" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
