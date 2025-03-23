<?php
session_start();
include 'db.php';

// Redirect if teacher is not logged in
if (!isset($_SESSION['teacher_logged_in'])) {
    header("Location: Teacher_login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$teacher_department = $_SESSION['teacher_department']; // Department from session

// Fetch students of the same department as the teacher
$query = "SELECT username, email, department, score, password FROM users WHERE department = ? ORDER BY score DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $teacher_department);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}
mysqli_stmt_close($stmt);

// Separate Passed & Failed students
$passMark = 35;
$passed = array_filter($students, fn($student) => $student['score'] >= $passMark);
$failed = array_filter($students, fn($student) => $student['score'] < $passMark);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            width: 280px;
            height: 100vh;
            background: #2C3E50;
            color: white;
            padding: 20px;
            position: fixed;
            transition: 0.3s;
        }
        .sidebar h4 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 10px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .sidebar i {
            margin-right: 10px;
        }
        .content {
            margin-left: 300px;
            padding: 30px;
        }
        .table thead {
            background: #343a40;
            color: white;
        }
        .score-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
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
        <h4><i class="fas fa-chalkboard-teacher"></i> Teacher Dashboard</h4>
        <a href="teacher_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="QuizSystem.php" class="btn btn-danger w-100 mt-4"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
        
    <!-- Main Content -->
    <div class="content">
        <h2 class="text-center"><i class="fas fa-user-tie"></i> Welcome, <?= htmlspecialchars($_SESSION['teacher_username']); ?></h2>
        <h4 class="text-center text-primary">Department: <?= htmlspecialchars($teacher_department); ?></h4>

        <!-- Passed Students -->
        <div class="card my-4">
            <div class="card-header bg-success text-white">
                <h4><i class="fas fa-check-circle"></i> Passed Students</h4>
            </div>
            <div class="card-body">
                <?php if (count($passed) > 0): ?>
                    <table id="passTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roll No.</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sr = 1; foreach ($passed as $student): ?>
                            <tr>
                                <td><?= $sr++; ?></td>
                                <td><?= htmlspecialchars($student['username']); ?></td>
                                <td><?= htmlspecialchars($student['email']); ?></td>
                                <td><?= htmlspecialchars($student['password']); ?></td>
                                <td><span class="score-badge bg-success text-white"><?= $student['score']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No passed students found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Failed Students -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4><i class="fas fa-times-circle"></i> Failed Students</h4>
            </div>
            <div class="card-body">
                <?php if (count($failed) > 0): ?>
                    <table id="failTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roll No.</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sr = 1; foreach ($failed as $student): ?>
                            <tr>
                                <td><?= $sr++; ?></td>
                                <td><?= htmlspecialchars($student['username']); ?></td>
                                <td><?= htmlspecialchars($student['email']); ?></td>
                                <td><?= htmlspecialchars($student['password']); ?></td>
                                <td><span class="score-badge bg-danger text-white"><?= $student['score']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No failed students found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- jQuery and DataTables Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#passTable, #failTable').DataTable();
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
