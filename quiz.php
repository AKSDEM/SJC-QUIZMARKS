<?php
session_start();
include 'db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user has already passed the quiz (pass mark = 35)
$checkPassQuery = "SELECT score FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $checkPassQuery);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

$passMark = 35;
if ($user && $user['score'] !== null && $user['score'] >= $passMark) {
    header("Location: result.php");
    exit();
}

// Fetch 50 random questions from the database
$questionQuery = "SELECT * FROM questions ORDER BY RAND() LIMIT 50";
$questionResult = mysqli_query($conn, $questionQuery);
$questions = mysqli_fetch_all($questionResult, MYSQLI_ASSOC);

// Store questions in session
$_SESSION['questions'] = $questions;
$_SESSION['user_answers'] = [];
$_SESSION['current_page'] = 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['finish'])) {
    $correct_count = 0;
    
    foreach ($_SESSION['questions'] as $question) {
        $question_id = $question['id'];
        $correct_answer = strtoupper(trim($question['correct_answer']));
        $user_answer = isset($_POST['answers'][$question_id]) ? strtoupper(trim($_POST['answers'][$question_id])) : '';
        
        if ($correct_answer == $user_answer) {
            $correct_count++;
        }
    }
    
    // Calculate final score
    $final_score = ($correct_count / 50) * 100;

    // Update user's score in database
    $updateQuery = "UPDATE users SET score = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "di", $final_score, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: result.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Quiz</h2>
        <form method="post">
            <?php foreach ($_SESSION['questions'] as $index => $question) { ?>
                <div class="mb-4">
                    <p><strong>Q<?= $index + 1; ?>:</strong> <?= htmlspecialchars($question['question']); ?></p>
                    <div>
                        <label><input type="radio" name="answers[<?= $question['id']; ?>]" value="A"> <?= htmlspecialchars($question['option_a']); ?></label><br>
                        <label><input type="radio" name="answers[<?= $question['id']; ?>]" value="B"> <?= htmlspecialchars($question['option_b']); ?></label><br>
                        <label><input type="radio" name="answers[<?= $question['id']; ?>]" value="C"> <?= htmlspecialchars($question['option_c']); ?></label><br>
                        <label><input type="radio" name="answers[<?= $question['id']; ?>]" value="D"> <?= htmlspecialchars($question['option_d']); ?></label>
                    </div>
                </div>
            <?php } ?>
            <button type="submit" name="finish" class="btn btn-success">Finish</button>
        </form>
    </div>
</body>
</html>
