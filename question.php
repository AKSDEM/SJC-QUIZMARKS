<?php
session_start();
include 'db.php'; // Database connection

// Check if user is logged in; if not, redirect to login page.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if user has already passed the test (pass mark = 35)
$user_id = $_SESSION['user_id'];
$userQuery = "SELECT score FROM users WHERE id = '$user_id'";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);
$passMark = 35; // pass mark

if ($user && $user['score'] !== null && $user['score'] >= $passMark) {
    // Already passed; redirect or show view your score option
    header("Location: result.php");
    exit();
}

// Reset quiz completely on login or retest
if (!isset($_SESSION['quiz_started']) || $_SESSION['quiz_started'] !== true) {
    $_SESSION['quiz_started'] = true;
    $_SESSION['current_page'] = 0;
    $_SESSION['correct_answers'] = 0;
    $_SESSION['user_answers'] = [];

    // Get all previously attempted questions for this user.
    $prevQuery = "SELECT question_id FROM user_attempts WHERE user_id = '$user_id'";
    $prevResult = mysqli_query($conn, $prevQuery);

    $attemptedQuestions = [];
    while ($row = mysqli_fetch_assoc($prevResult)) {
        $attemptedQuestions[] = $row['question_id'];
    }

    // Prepare a list of new questions excluding those already attempted.
    if (!empty($attemptedQuestions)) {
        $attemptedStr = implode(",", $attemptedQuestions);
        $newQuery = "SELECT * FROM questions WHERE id NOT IN ($attemptedStr) ORDER BY RAND() LIMIT " . (50 - count($attemptedQuestions));
        $newResult = mysqli_query($conn, $newQuery);
        $newQuestions = [];
        while ($row = mysqli_fetch_assoc($newResult)) {
            $newQuestions[] = $row;
        }
    } else {
        $newQuestions = [];
    }

    // Count how many new questions we got.
    $numNew = count($newQuestions);

    // If we didn't get enough new questions, fill the remainder from the full pool.
    if ($numNew < 50) {
        $needed = 50 - $numNew;
        if (!empty($attemptedQuestions)) {
            // Exclude new ones to avoid duplicates.
            $newIds = array_column($newQuestions, 'id');
            if (!empty($newIds)) {
                $excludeStr = implode(",", $newIds);
                $fillQuery = "SELECT * FROM questions WHERE id NOT IN ($excludeStr) ORDER BY RAND() LIMIT $needed";
            } else {
                $fillQuery = "SELECT * FROM questions ORDER BY RAND() LIMIT $needed";
            }
        } else {
            $fillQuery = "SELECT * FROM questions ORDER BY RAND() LIMIT $needed";
        }
        $fillResult = mysqli_query($conn, $fillQuery);
        $fillQuestions = [];
        while ($row = mysqli_fetch_assoc($fillResult)) {
            $fillQuestions[] = $row;
        }
        // Merge the new questions with the fill-in questions.
        $questions = array_merge($newQuestions, $fillQuestions);
    } else {
        $questions = $newQuestions;
    }

    // Ensure exactly 50 questions have been selected.
    if (count($questions) < 50) {
        die("Not enough questions in the database!");
    }

    // Shuffle the final question set
    shuffle($questions);
    $_SESSION['questions'] = $questions;
}

// Handle answer submission and navigation.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Save answer for the current question.
    $currentQuestion = $_SESSION['questions'][$_SESSION['current_page']];
    $qID = $currentQuestion['id'];
    if (isset($_POST["answer"])) {
        $_SESSION['user_answers'][$qID] = $_POST["answer"];
    }

    // Navigation
    if (isset($_POST['next'])) {
        $_SESSION['current_page']++;
    }
    if (isset($_POST['prev'])) {
        $_SESSION['current_page']--;
    }

    // Finish quiz and calculate score.
    if (isset($_POST['finish'])) {
        $_SESSION['correct_answers'] = 0; // Reset correct count
        foreach ($_SESSION['questions'] as $question) {
            $question_id = $question['id'];
            $correct_answer = $question['correct_answer'];
            if (isset($_SESSION['user_answers'][$question_id]) && $_SESSION['user_answers'][$question_id] == $correct_answer) {
                $_SESSION['correct_answers']++;
            }
        }
        // Each question is worth 2 marks (50 questions = 100 marks)
        $_SESSION['final_score'] = $_SESSION['correct_answers'] * 2;

        // Update user score in the database
        $final_score = $_SESSION['final_score'];
        $updateQuery = "UPDATE users SET score = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ii", $final_score, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Log attempted questions for future retests.
        foreach ($_SESSION['questions'] as $question) {
            $question_id = $question['id'];
            $attemptQuery = "INSERT INTO user_attempts (user_id, question_id, attempt_time) VALUES ('$user_id', '$question_id', NOW())";
            mysqli_query($conn, $attemptQuery);
        }

        // Optionally, you can store more user data in session if needed (name, department) for result display.
        $_SESSION['user_name'] = $user['name'] ?? "User";
        // Assuming the department is stored in a separate session variable or user table.
        $_SESSION['user_department'] = $user['department'] ?? "N/A";

        // Destroy the quiz session data (or you may want to keep some for result page display)
        // Note: Do not call session_destroy() here if you need to display results via session.
        header("Location: result.php");
        exit();
    }
}

// Pagination settings: one question per page.
$questionsPerPage = 1;
$currentIndex = $_SESSION['current_page'];
$totalQuestions = count($_SESSION['questions']);
$totalPages = $totalQuestions; // Since one question per page.
$currentQuestion = $_SESSION['questions'][$currentIndex];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Quiz | Question <?php echo $currentIndex + 1; ?> of <?php echo $totalPages; ?></title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Arial', sans-serif;
    }
    .quiz-container {
      max-width: 700px;
      margin: 30px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .question-text {
      font-size: 1.2rem;
      margin-bottom: 20px;
    }
    .options label {
      display: block;
      margin-bottom: 10px;
      font-size: 1.1rem;
    }
    .navigation-buttons {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="quiz-container">
    <h2 class="mb-3 text-center">Quiz</h2>
    <p class="text-center">Question <?php echo $currentIndex + 1; ?> of <?php echo $totalPages; ?></p>
    <form method="post">
      <div class="mb-4">
        <p class="question-text">
          <?php echo ($currentIndex + 1) . ". " . htmlspecialchars($currentQuestion['question']); ?>
        </p>
        <div class="options">
          <label>
            <input type="radio" name="answer" value="A"
              <?php echo (isset($_SESSION['user_answers'][$currentQuestion['id']]) && $_SESSION['user_answers'][$currentQuestion['id']] == 'A') ? 'checked' : ''; ?>
            > <?php echo htmlspecialchars($currentQuestion['option_a']); ?>
          </label>
          <label>
            <input type="radio" name="answer" value="B"
              <?php echo (isset($_SESSION['user_answers'][$currentQuestion['id']]) && $_SESSION['user_answers'][$currentQuestion['id']] == 'B') ? 'checked' : ''; ?>
            > <?php echo htmlspecialchars($currentQuestion['option_b']); ?>
          </label>
          <label>
            <input type="radio" name="answer" value="C"
              <?php echo (isset($_SESSION['user_answers'][$currentQuestion['id']]) && $_SESSION['user_answers'][$currentQuestion['id']] == 'C') ? 'checked' : ''; ?>
            > <?php echo htmlspecialchars($currentQuestion['option_c']); ?>
          </label>
          <label>
            <input type="radio" name="answer" value="D"
              <?php echo (isset($_SESSION['user_answers'][$currentQuestion['id']]) && $_SESSION['user_answers'][$currentQuestion['id']] == 'D') ? 'checked' : ''; ?>
            > <?php echo htmlspecialchars($currentQuestion['option_d']); ?>
          </label>
        </div>
      </div>
      <div class="navigation-buttons d-flex justify-content-between">
        <?php if ($currentIndex > 0) { ?>
          <button type="submit" name="prev" class="btn btn-secondary">Previous</button>
        <?php } else { ?>
          <div></div>
        <?php } ?>
        <?php if ($currentIndex < $totalPages - 1) { ?>
          <button type="submit" name="next" class="btn btn-primary">Next</button>
        <?php } else { ?>
          <button type="submit" name="finish" class="btn btn-success">Finish</button>
        <?php } ?>
      </div>
    </form>
  </div>
  
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
