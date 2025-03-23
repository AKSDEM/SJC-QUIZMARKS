<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $department = $_POST['department'];
    $password = $_POST['password'];

    // Fetch user from the database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND department='$department'");
    $user = mysqli_fetch_assoc($result);

    // Compare plain text passwords instead of password_verify()
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];

        // If the user has already passed the test, show a message
        if ($user['score'] !== NULL && $user['score'] >= 50) {
            echo "<div class='alert alert-success text-center'>You have already passed the test!</div>";
        } else {
            header("Location: quiz.php");
            exit();
        }
    } else {
        echo "<script>
                alert('❌ Invalid credentials! Please try again.');
                window.location.href = 'login.php';
              </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | Quiz System</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <style>
    body {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      font-family: 'Arial', sans-serif;
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 420px;
    }
    .login-container h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #333;
      font-weight: 700;
    }
    .form-label {
      font-weight: 600;
      color: #555;
    }
    .form-control {
      border-radius: 6px;
      border: 1px solid #ccc;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .form-control:focus {
      border-color: #6a11cb;
      box-shadow: 0 0 8px rgba(106, 17, 203, 0.3);
    }
    .btn-primary {
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      border: none;
      border-radius: 6px;
      font-weight: 600;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(106, 17, 203, 0.3);
    }
    .register-link {
      text-align: center;
      margin-top: 20px;
    }
    .register-link a {
      color: #6a11cb;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    .register-link a:hover {
      color: #2575fc;
      text-decoration: underline;
    }
    /* Responsive adjustments */
    @media (max-width: 576px) {
      .login-container {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <form method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username:</label>
        <input type="text" name="username" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="department" class="form-label">Department:</label>
        <select name="department" class="form-control" required>
          <option value="" disabled selected>Select your department</option>
          <option value="Zoology">Zoology</option>
          <option value="Sociology">Sociology</option>
          <option value="Political Science">Political Science</option>
          <option value="Physics">Physics</option>
          <option value="Mathematics">Mathematics</option>
          <option value="History">History</option>
          <option value="English">English</option>
          <option value="Education">Education</option>
          <option value="Economics">Economics</option>
          <option value="Commerce">Commerce</option>
          <option value="Chemistry">Chemistry</option>
          <option value="Business Administration">Business Administration</option>
          <option value="Botany">Botany</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" name="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <div class="register-link">
        <small>Don't have an account? <a href="register.php">Register here</a></small>
      </div>
    </form>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
