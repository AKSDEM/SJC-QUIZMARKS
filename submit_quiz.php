<?php
session_start();
include 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate that answers were submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['answers'])) {
    $answers = $_POST['answers'];
    $correct_count = 0;
    $total_questions = count($answers);
    $attempt_time = date('Y-m-d H:i:s'); // Store attempt timestamp

    // Check answers against the database
    foreach ($answers as $question_id => $user_answer) {
        $query = "SELECT correct_answer FROM questions WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $question_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($row) {
            $correct_answer = strtoupper(trim($row['correct_answer'])); // Ensure consistency
            $user_answer = strtoupper(trim($user_answer)); // Convert user input to uppercase

            if ($correct_answer == $user_answer) {
                $correct_count++;
            }

            // ✅ Store the attempt details (Selected & Correct Option) in user_attempts
            $insertQuery = "INSERT INTO user_attempts (user_id, question_id, attempt_time, selected_option, correct_option) 
                            VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmt, "iisss", $user_id, $question_id, $attempt_time, $user_answer, $correct_answer);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    // Calculate final score
    $final_score = ($correct_count / $total_questions) * 100;

    // Update user score in the database
    $updateQuery = "UPDATE users SET score = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, "di", $final_score, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Redirect to result page
    header("Location: /demo/result.php");
    exit();
} else {
    echo "<script>alert('No answers submitted.'); window.location.href='/demo/quiz.php';</script>";
    exit();
}
?>
