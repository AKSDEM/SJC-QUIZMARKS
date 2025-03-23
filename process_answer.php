<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_answer = strtoupper(trim($_POST['answer'])); // Ensure case-insensitive comparison
    $current_index = $_SESSION['current_question'];
    $questions = $_SESSION['questions'];
    $correct_answer = strtoupper(trim($questions[$current_index]['correct_answer']));

    if ($user_answer === $correct_answer) {
        $_SESSION['correct_answers']++; // Increment correct answers count
    }

    $_SESSION['current_question']++; // Move to the next question

    if ($_SESSION['current_question'] >= count($questions)) {
        header("Location: result.php");
    } else {
        header("Location: question.php");
    }
    exit;
}
?>

