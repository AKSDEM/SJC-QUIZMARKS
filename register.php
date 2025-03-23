<?php
include 'db.php';

// Department codes
$departments = [
    "Zoology" => "ZOO",
    "Sociology" => "SCO",
    "Political Science" => "POL",
    "Physics" => "PHY",
    "Mathematics" => "MAT",
    "History" => "HIS",
    "English" => "ENG",
    "Education" => "EDU",
    "Economics" => "ECO",
    "Commerce" => "COM",
    "Chemistry" => "CHE",
    "Business Administration" => "BUS",
    "Botany" => "BOT"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $department = $_POST['department'];
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $deptCode = $departments[$department] ?? null;

    // Validate password format (YY + DeptCode + 3 to 5 Digits)
    if (!$deptCode || !preg_match("/^\\d{2}$deptCode\\d{3,5}$/", $password)) {
        echo "<script>
                alert('❌ Invalid password format! Password must be in the format: YYDEPT### (e.g., 24SCO0245)');
                window.location.href = 'register.php';
              </script>";
        exit();
    }

    // Store password in plain text (not hashed) for admin visibility
    $query = "INSERT INTO users (username, department, email, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $username, $department, $email, $password);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('✅ Registration successful!');
                    window.location.href = 'QuizSystem.php';
                  </script>";
        } else {
            echo "<script>
                    alert('❌ Error: " . mysqli_stmt_error($stmt) . "');
                    window.location.href = 'register.php';
                  </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
                alert('❌ Database error: " . mysqli_error($conn) . "');
                window.location.href = 'register.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | Quiz System</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome for icons -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <!-- Custom CSS -->
  <style>
    body {
      background: url('https://source.unsplash.com/1600x900/?education,books') no-repeat center center fixed;
      background-size: cover;
      position: relative;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      color: #fff;
    }
    /* Overlay for darkening the background image */
    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: -1;
    }
    .card {
      background: rgba(0, 0, 0, 0.85);
      border: none;
      border-radius: 12px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
      padding: 30px;
      width: 100%;
      max-width: 450px;
    }
    .card h3 {
      margin-bottom: 25px;
      font-weight: 700;
    }
    .form-label {
      font-weight: 600;
    }
    .form-control {
      border-radius: 6px;
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid #ccc;
      color: #333;
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
    .password-container {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      color: #6a11cb;
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
    @media (max-width: 576px) {
      .card {
        padding: 20px;
        margin: 10px;
      }
    }
  </style>
</head>
<body>
  <div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card">
      <h3 class="text-center">Student Registration</h3>
      <form action="register.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Full Name</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Your full name" required>
        </div>
        <div class="mb-3">
          <label for="department" class="form-label">Department</label>
          <select name="department" id="department" class="form-control" required>
            <option value="">-- Select Department --</option>
            <?php foreach ($departments as $dept => $code) { ?>
              <option value="<?php echo $dept; ?>"><?php echo $dept; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
        </div>
        <div class="mb-3 password-container">
          <label for="password" class="form-label">Password</label>
          <input type="text" class="form-control" name="password" id="password" placeholder="e.g., 24SCO0245" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
      </form>
      <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
