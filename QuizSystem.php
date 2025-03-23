<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quiz System | St. Joseph Institution</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f4f7fc;
      font-family: 'Poppins', sans-serif;
    }

    /* Navbar */
    .navbar {
      background: #2C3E50;
      padding: 10px 0;
    }
    .navbar-brand {
      font-weight: bold;
      font-size: 1.6rem;
      color: #fff;
    }
    .navbar-nav .nav-link {
      color: #fff;
      font-size: 1rem;
      margin: 0 10px;
    }
    .navbar-toggler {
      border: none;
    }

    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, #0052D4, #4364F7);
      color: white;
      padding: 100px 15px;
      text-align: center;
    }
    .hero-section h1 {
      font-size: 2.5rem;
      font-weight: 700;
    }
    .hero-section p {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }
    .hero-section .btn {
      font-size: 1.1rem;
      padding: 12px 25px;
      border-radius: 50px;
      margin: 5px;
    }

    /* Features Section */
    .features-section {
      padding: 60px 15px;
      background: white;
    }
    .features-section h2 {
      text-align: center;
      font-weight: bold;
      color: #333;
      margin-bottom: 40px;
    }
    .feature-card {
      text-align: center;
      padding: 30px;
      border: none;
      border-radius: 10px;
      transition: all 0.3s ease;
      background: #f9f9f9;
      box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }
    .feature-card:hover {
      transform: translateY(-5px);
    }
    .feature-card i {
      font-size: 50px;
      color: #0052D4;
      margin-bottom: 15px;
    }
    .feature-card h3 {
      font-size: 1.3rem;
      color: #333;
    }
    .feature-card p {
      font-size: 1rem;
      color: #666;
    }

    /* Footer */
    .footer {
      background: #2C3E50;
      color: white;
      padding: 20px 0;
      text-align: center;
    }
    .footer a {
      color: white;
      text-decoration: none;
      margin: 0 10px;
      font-size: 1rem;
    }
    .footer a:hover {
      color: #ffcc00;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .hero-section {
        padding: 60px 15px;
      }
      .hero-section h1 {
        font-size: 2rem;
      }
      .hero-section p {
        font-size: 1rem;
      }
      .feature-card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#"><i class="fas fa-graduation-cap"></i> Quiz System</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fas fa-user-cog"></i> More
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="admin_login.php">Admin Login</a></li>
              <li><a class="dropdown-item" href="teacher_login.php">Teacher Login</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <h1>Welcome to St. Joseph Quiz System</h1>
      <p>Test your knowledge with engaging quizzes and track your progress.</p>
      <a href="register.php" class="btn btn-warning text-dark"><i class="fas fa-user-plus"></i> Get Started</a>
      <a href="login.php" class="btn btn-outline-light"><i class="fas fa-sign-in-alt"></i> Login</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features-section">
    <div class="container">
      <h2>Why Choose Us?</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="feature-card">
            <i class="fas fa-question-circle"></i>
            <h3>Interactive Quizzes</h3>
            <p>Engage with fun and challenging quizzes on various subjects.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card">
            <i class="fas fa-chart-line"></i>
            <h3>Track Progress</h3>
            <p>Monitor your performance and improve over time.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card">
            <i class="fas fa-trophy"></i>
            <h3>Leaderboard</h3>
            <p>Compete with others and climb the leaderboard.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <p>&copy; 2025 Quiz System | St. Joseph Institution. All rights reserved.</p>
      <p>Developed by <strong>Khiongri A.</strong>  & <strong>Manas Gogoi</strong></p>
      <p><strong>A.K Computer Entrepreneur</strong> - Delivering Innovative Digital Solutions</p>
      <p>
        <a href="#">Privacy Policy</a> | 
        <a href="#">Terms of Service</a> | 
        <a href="#">Contact Us</a>
      </p>
    </div>
  </footer>



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
