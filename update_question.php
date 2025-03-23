<?php
include 'db.php';

// Check if the ID is set in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Invalid Question ID!</div>");
}

$question_id = $_GET['id'];

// Fetch the existing question details
$query = "SELECT * FROM questions WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $question_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$question = mysqli_fetch_assoc($result);

if (!$question) {
    die("<div class='alert alert-danger text-center mt-5'>❌ Question not found!</div>");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_text = trim($_POST['question']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_answer = trim($_POST['correct_answer']);

    // Update the question in the database
    $update_query = "UPDATE questions SET question=?, option_a=?, option_b=?, option_c=?, option_d=?, correct_answer=? WHERE id=?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $question_id);

    if (mysqli_stmt_execute($update_stmt)) {
        echo "<script>
                alert('✅ Question updated successfully!');
                window.location.href = 'admin_dashboard.php';
              </script>";
        exit();
    } else {
        echo "<div class='alert alert-danger text-center'>❌ Error updating question: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 260px;
            background: #2C3E50;
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px;
            display: block;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: 0.3s;
            width: 100%;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .sidebar i {
            margin-right: 8px;
        }
        .content {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }
        .btn-update {
            background: #28a745;
            color: white;
            font-weight: bold;
        }
        .btn-update:hover {
            background: #218838;
        }
        .btn-cancel {
            background: #dc3545;
            color: white;
            font-weight: bold;
        }
        .btn-cancel:hover {
            background: #c82333;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3><i class="fas fa-cogs"></i> Admin Panel</h3>
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="add_question.php"><i class="fas fa-plus-circle"></i> Add Question</a>
        <a href="manage_questions.php"><i class="fas fa-edit"></i> Manage Questions</a>
        <a href="logout.php" class="btn btn-danger w-100 mt-4"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <!-- Main Content -->
    <div class="content">
        <h2 class="text-center"><i class="fas fa-edit"></i> Updating Question</h2>
        
        <div class="form-container">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Question:</label>
                    <textarea class="form-control" name="question" required><?= htmlspecialchars($question['question']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Option A:</label>
                    <input type="text" class="form-control" name="option_a" value="<?= htmlspecialchars($question['option_a']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Option B:</label>
                    <input type="text" class="form-control" name="option_b" value="<?= htmlspecialchars($question['option_b']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Option C:</label>
                    <input type="text" class="form-control" name="option_c" value="<?= htmlspecialchars($question['option_c']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Option D:</label>
                    <input type="text" class="form-control" name="option_d" value="<?= htmlspecialchars($question['option_d']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Correct Answer:</label>
                    <select class="form-control" name="correct_answer" required>
                        <option value="A" <?= ($question['correct_answer'] == 'A') ? 'selected' : ''; ?>>A</option>
                        <option value="B" <?= ($question['correct_answer'] == 'B') ? 'selected' : ''; ?>>B</option>
                        <option value="C" <?= ($question['correct_answer'] == 'C') ? 'selected' : ''; ?>>C</option>
                        <option value="D" <?= ($question['correct_answer'] == 'D') ? 'selected' : ''; ?>>D</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-update w-100"><i class="fas fa-save"></i> Update Question</button>
                <a href="admin_dashboard.php" class="btn btn-cancel w-100 mt-2"><i class="fas fa-times"></i> Cancel</a>
            </form>
        </div>
    </div>

</body>
</html>
