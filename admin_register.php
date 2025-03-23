<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>
                alert('❌ Passwords do not match!');
                setTimeout(() => { window.location.href = 'admin_register.php'; }, 2000);
              </script>";
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into admins table
    $query = "INSERT INTO admins (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('✅ Admin registered successfully!');
                setTimeout(() => { window.location.href = 'admin_login.php'; }, 2000);
              </script>";
    } else {
        echo "<script>
                alert('❌ Error: " . mysqli_error($conn) . "');
                setTimeout(() => { window.location.href = 'admin_register.php'; }, 2000);
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
        <h3 class="text-center">Admin Register</h3>
        <form method="POST">
            <div class="mb-3">
                <label>Username:</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="text-center mt-2">Already an admin? <a href="admin_login.php">Login</a></p>
    </div>
</body>
</html>
