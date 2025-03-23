<?php
include 'db.php';

// Fetch questions from the database
$result = mysqli_query($conn, "SELECT * FROM questions");

if (!$result) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Error fetching questions: " . mysqli_error($conn) . "</div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>View Questions | Admin Panel</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f7fc;
      margin: 0;
      padding: 0;
      display: flex;
      min-height: 100vh;
    }
    /* Sidebar Styling */
    .sidebar {
      width: 260px;
      background: #2C3E50;
      color: #fff;
      padding: 20px;
      position: fixed;
      height: 100vh;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .sidebar h4 {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 25px;
    }
    .sidebar a {
      display: block;
      width: 100%;
      padding: 12px;
      color: #fff;
      text-decoration: none;
      text-align: center;
      border-radius: 5px;
      margin-bottom: 10px;
      transition: background 0.3s ease;
      font-weight: 500;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background: #495057;
    }
    .sidebar i {
      margin-right: 10px;
    }
    .sidebar .logout {
      background: #dc3545;
    }
    /* Main Content Styling */
    .main-content {
      margin-left: 280px;
      padding: 30px;
      width: calc(100% - 280px);
    }
    .main-content h2 {
      margin-bottom: 30px;
      text-align: center;
      color: #343a40;
      font-weight: 600;
    }
    .table-responsive {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
      padding: 20px;
    }
    .table th, .table td {
      vertical-align: middle !important;
      text-align: center;
    }
    .table-hover tbody tr:hover {
      background: #f1f1f1;
    }
    .correct-answer {
      font-weight: bold;
      color: #28a745;
    }
    .edit-btn, .delete-btn {
      text-decoration: none;
      padding: 6px 12px;
      font-size: 14px;
      border-radius: 5px;
      font-weight: bold;
      transition: background 0.3s ease;
    }
    .edit-btn {
      background: #ffc107;
      color: black;
    }
    .edit-btn:hover {
      background: #e0a800;
    }
    .delete-btn {
      background: #dc3545;
      color: white;
    }
    .delete-btn:hover {
      background: #c82333;
    }
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        padding: 15px;
      }
      .main-content {
        margin-left: 0;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h4><i class="fas fa-user-shield"></i> Admin Panel</h4>
    <a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
    <a href="add_question.php"><i class="fas fa-plus-circle"></i> Add Question</a>
    <a href="view_question.php" class="active"><i class="fas fa-list"></i> View Questions</a>
    <a href="admin_panel.php"><i class="fas fa-arrow-left"></i> Back to Admin Panel</a>
    <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2><i class="fas fa-list"></i> List of Questions</h2>
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="table-dark">
          <tr>
            <th>S/N</th>
            <th>Question</th>
            <th>Option A</th>
            <th>Option B</th>
            <th>Option C</th>
            <th>Option D</th>
            <th>Correct Answer</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $count = 1;
          while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td><?= $count++; ?></td>
              <td><?= htmlspecialchars($row['question']); ?></td>
              <td><?= htmlspecialchars($row['option_a']); ?></td>
              <td><?= htmlspecialchars($row['option_b']); ?></td>
              <td><?= htmlspecialchars($row['option_c']); ?></td>
              <td><?= htmlspecialchars($row['option_d']); ?></td>
              <td class="correct-answer"><?= htmlspecialchars($row['correct_answer']); ?></td>
              <td>
                <a href="update_question.php?id=<?= $row['id']; ?>" class="edit-btn">✏️ Edit</a>
                <a href="delete_question.php?id=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this question?');">🗑 Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
