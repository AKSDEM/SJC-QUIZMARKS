<?php
session_start();
include 'db.php';

// Redirect if admin is not logged in.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Build list of departments from the database or a known list.
$departments = ["Zoology", "Sociology", "Political Science", "Physics", "Mathematics", "History", "English", "Education", "Economics", "Commerce", "Chemistry", "Business Administration", "Botany"];

// If a department is selected via GET parameter, then filter results.
$selectedDepartment = isset($_GET['department']) ? $_GET['department'] : '';

$users = [];
if ($selectedDepartment != '') {
    // Fetch users along with their attempt count from user_attempts table.
    $query = "SELECT u.id, u.username, u.department, u.email, u.score, u.password, 
                     COUNT(ua.id) AS attempt_count 
              FROM users u
              LEFT JOIN user_attempts ua ON u.id = ua.user_id
              WHERE u.department='$selectedDepartment'
              GROUP BY u.id
              ORDER BY u.score DESC";
    
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    // Separate passed and failed students.
    $passMark = 35;
    $passed = array_filter($users, function ($user) use ($passMark) {
        return $user['score'] >= $passMark;
    });
    $failed = array_filter($users, function ($user) use ($passMark) {
        return $user['score'] < $passMark;
    });
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- DataTables CSS -->
  <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="admin_dashboard.php">📊 Dashboard</a>
    <a href="add_question.php">➕ Add Questions</a>
    <a href="view_question.php">📜 View Questions</a>
    <a href="QuizSystem.php">🚪 Logout</a>
    <a href="Teacher_register.php" class="btn btn-success w-100 mt-3">👨‍🏫 Add Teacher</a>
  </div>

  <!-- Main Content -->
  <div class="content">
    <h2 class="text-center mb-4">Department-wise Student Scores</h2>

    <!-- Department Filter -->
    <div class="department-filter">
      <form method="GET" class="input-group mb-4">
        <select name="department" class="form-select" required>
          <option value="">Select Department</option>
          <?php foreach ($departments as $dept): ?>
            <option value="<?= $dept; ?>" <?= ($selectedDepartment === $dept) ? 'selected' : ''; ?>>
              <?= $dept; ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-primary" type="submit">Filter</button>
      </form>
    </div>

    <?php if ($selectedDepartment != ''): ?>
      <h3 class="mb-3 text-center"><?= htmlspecialchars($selectedDepartment); ?> Students</h3>

      <!-- Passed Students -->
      <div class="mb-5">
        <h4 class="text-success">Passed Students</h4>
        <?php if (count($passed) > 0): ?>
          <table id="passTable" class="table table-striped table-bordered">
            <thead class="table-dark">
              <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roll No.</th>
                <th>Score</th>
                <th>Attempts</th>
                <th>Marksheet</th> <!-- New Column -->
              </tr>
            </thead>
            <tbody>
              <?php $sr = 1; foreach ($passed as $user): ?>
              <tr>
                <td><?= $sr++; ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['password']); ?></td>
                <td>
                  <span class="score-badge bg-success text-white"><?= $user['score']; ?></span>
                </td>
                <td><?= $user['attempt_count']; ?></td>
                <td>
                  <a href="view_marksheet.php?user_id=<?= $user['id']; ?>" class="btn btn-primary btn-sm">View Marksheet</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="text-muted">No passed students found.</p>
        <?php endif; ?>
      </div>

      <!-- Failed Students -->
      <div>
        <h4 class="text-danger">Failed Students</h4>
        <?php if (count($failed) > 0): ?>
          <table id="failTable" class="table table-striped table-bordered">
            <thead class="table-dark">
              <tr>
                <th>Sr. No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roll No.</th>
                <th>Score</th>
                <th>Attempts</th>
                <th>Marksheet</th> <!-- New Column -->
              </tr>
            </thead>
            <tbody>
              <?php $sr = 1; foreach ($failed as $user): ?>
              <tr>
                <td><?= $sr++; ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['password']); ?></td>
                <td>
                  <span class="score-badge bg-danger text-white"><?= $user['score']; ?></span>
                </td>
                <td><?= $user['attempt_count']; ?></td>
                <td>
                  <a href="view_marksheet.php?user_id=<?= $user['id']; ?>" class="btn btn-primary btn-sm">View Marksheet</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="text-muted">No failed students found.</p>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
