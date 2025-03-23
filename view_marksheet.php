<?php
session_start();
include 'db.php';

// Admin authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("Invalid Request: No user ID provided!");
}

$user_id = intval($_GET['user_id']); // Sanitize input

// Fetch student details
$userQuery = "SELECT username, email FROM users WHERE id = $user_id";
$userResult = mysqli_query($conn, $userQuery);

if (!$userResult || mysqli_num_rows($userResult) == 0) {
    die("Error: Student not found in database.");
}

$user = mysqli_fetch_assoc($userResult);

// Fetch student's attempted questions with their responses
$marksheetQuery = "SELECT q.question, ua.selected_option, ua.correct_option, ua.attempt_time
                   FROM user_attempts ua
                   JOIN questions q ON ua.question_id = q.id
                   WHERE ua.user_id = $user_id
                   ORDER BY ua.attempt_time DESC";

$marksheetResult = mysqli_query($conn, $marksheetQuery);

// Check for query execution errors
if (!$marksheetResult) {
    die("SQL Error: " . mysqli_error($conn)); // Debugging step
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Marksheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h2>Marksheet for <?= htmlspecialchars($user['username']); ?></h2>
    <p>Email: <?= htmlspecialchars($user['email']); ?></p>

    <?php if (mysqli_num_rows($marksheetResult) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Question</th>
                    <th>Selected Option</th>
                    <th>Correct Answer</th>
                    <th>Attempt Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($marksheetResult)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['question']); ?></td>
                    <td><?= htmlspecialchars($row['selected_option']); ?></td>
                    <td><?= htmlspecialchars($row['correct_option']); ?></td>
                    <td><?= htmlspecialchars($row['attempt_time']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-muted">No attempt records found for this student.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</body>
</html>
