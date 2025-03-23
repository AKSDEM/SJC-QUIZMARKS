<?php
include 'db.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $option_a = mysqli_real_escape_string($conn, $_POST['option_a']);
    $option_b = mysqli_real_escape_string($conn, $_POST['option_b']);
    $option_c = mysqli_real_escape_string($conn, $_POST['option_c']);
    $option_d = mysqli_real_escape_string($conn, $_POST['option_d']);
    $correct_answer = strtoupper(trim(mysqli_real_escape_string($conn, $_POST['correct_answer'])));

    $valid_answers = ['A', 'B', 'C', 'D'];
    if (!in_array($correct_answer, $valid_answers)) {
        echo "<div class='alert alert-danger text-center'>❌ Error: Correct answer must be A, B, C, or D.</div>";
        exit;
    }

    $query = "INSERT INTO questions (question, option_a, option_b, option_c, option_d, correct_answer) 
              VALUES ('$question', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_answer')";

    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success text-center'>✅ Question added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Question | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
    }
    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #007bff;
      color: #fff;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100%;
    }
    .sidebar h2 {
      font-size: 22px;
      margin-bottom: 30px;
      text-align: center;
      font-weight: 600;
    }
    .sidebar a {
      text-decoration: none;
      color: #fff;
      font-size: 18px;
      margin-bottom: 15px;
      padding: 10px 15px;
      border-radius: 5px;
      transition: background 0.3s ease;
      display: block;
      text-align: center;
    }
    .sidebar a:hover {
      background-color: #0056b3;
    }
    /* Content */
    .content {
      margin-left: 270px;
      padding: 40px 30px;
      width: calc(100% - 270px);
    }
    .card-container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      max-width: 700px;
      margin: 0 auto;
    }
    h1 {
      font-size: 28px;
      color: #007bff;
      margin-bottom: 30px;
      text-align: center;
      font-weight: 600;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      font-weight: 500;
      color: #333;
      margin-bottom: 5px;
      display: block;
    }
    .form-control {
      border-radius: 5px;
      border: 1px solid #ccc;
      padding: 12px;
      font-size: 15px;
    }
    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
    }
    .btn-group {
      margin-top: 30px;
      display: flex;
      gap: 15px;
      justify-content: center;
    }
    .btn {
      padding: 12px 25px;
      font-size: 16px;
      border-radius: 5px;
      transition: background 0.3s ease, transform 0.2s ease;
      text-decoration: none;
      text-align: center;
    }
    .btn-primary {
      background-color: #007bff;
      color: #fff;
      border: none;
    }
    .btn-success {
      background-color: #28a745;
      color: #fff;
      border: none;
    }
    .btn-danger {
      background-color: #dc3545;
      color: #fff;
      border: none;
    }
    .btn:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }
    .alert {
      margin: 20px 0;
      border-radius: 5px;
      text-align: center;
    }
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding: 15px;
      }
      .content {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">🏠 Dashboard</a>
    <a href="add_question.php">➕ Add Question</a>
    <a href="view_question.php">📋 View Questions</a>
    <a href="logout.php">🚪 Logout</a>
  </div>
  <!-- Content -->
  <div class="content">
    <div class="card-container">
      <h1>Add New Question</h1>
      <form method="POST">
        <div class="form-group">
          <label for="question">Question:</label>
          <textarea name="question" class="form-control" rows="3" placeholder="Enter your question here" required></textarea>
        </div>
        <div class="form-group">
          <label for="option_a">Option A:</label>
          <input type="text" name="option_a" class="form-control" placeholder="Enter option A" required>
        </div>
        <div class="form-group">
          <label for="option_b">Option B:</label>
          <input type="text" name="option_b" class="form-control" placeholder="Enter option B" required>
        </div>
        <div class="form-group">
          <label for="option_c">Option C:</label>
          <input type="text" name="option_c" class="form-control" placeholder="Enter option C" required>
        </div>
        <div class="form-group">
          <label for="option_d">Option D:</label>
          <input type="text" name="option_d" class="form-control" placeholder="Enter option D" required>
        </div>
        <div class="form-group">
          <label for="correct_answer">Correct Answer:</label>
          <select name="correct_answer" class="form-control" required>
            <option value="A">Option A</option>
            <option value="B">Option B</option>
            <option value="C">Option C</option>
            <option value="D">Option D</option>
          </select>
        </div>
        <div class="btn-group">
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="admin_dashboard.php" class="btn btn-danger">Exit</a>
        </div>
      </form>
    </div>
  </div>
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
