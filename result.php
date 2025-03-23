<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details and score
$query = "SELECT username, department, score FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Ensure score is available
$final_score = isset($user['score']) ? $user['score'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .result-container {
            background: #fff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 90%;
        }
        .result-container h2 {
            color: #007bff;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .result-container p {
            font-size: 18px;
            margin: 10px 0;
        }
        .score {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            color: <?= ($final_score >= 35) ? '#28a745' : '#dc3545' ?>;
        }
        .btn-custom {
            margin-top: 30px;
            padding: 10px 25px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="result-container">
        <h2>Quiz Completed!</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['username']); ?></p>
        <p><strong>Department:</strong> <?= htmlspecialchars($user['department']); ?></p>
        <p class="score"><strong>Your Score:</strong> <?= $final_score; ?> out of 100</p>
        
        <?php if ($final_score >= 35): ?>
            <p class="text-success"><strong>🎉 Congratulations! You have passed the quiz.</strong></p>
        <?php else: ?>
            <p class="text-danger"><strong>😢 Unfortunately, you did not pass. Try again!</strong></p>
        <?php endif; ?>

        <a href="QuizSystem.php" class="btn btn-danger btn-custom">Exit</a>
    </div>
</body>
</html>
