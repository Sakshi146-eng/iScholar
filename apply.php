<?php
session_start();
include 'db.php';

$message = '';
$message_class = '';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$scholarship_id = $_POST['scholarship_id'];

// Check if already applied
$check = $conn->query("SELECT * FROM applications WHERE student_id = $student_id AND scholarship_id = $scholarship_id");
if ($check->num_rows > 0) {
    $message = "You've already applied for this scholarship.";
    $message_class = 'error';
} else {
    // Insert application
    $sql = "INSERT INTO applications (student_id, scholarship_id) VALUES ($student_id, $scholarship_id)";
    if ($conn->query($sql)) {
        $message = "Application submitted successfully!";
        $message_class = 'success';
    } else {
        $message = "Error: " . $conn->error;
        $message_class = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Application - Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 24px 24px 24px;
            text-align: center;
        }
        h2 {
            color: #1976d2;
            margin-bottom: 18px;
        }
        .msg {
            font-size: 1.1rem;
            font-weight: bold;
            margin: 24px 0 10px 0;
            padding: 16px 10px;
            border-radius: 8px;
        }
        .success {
            background: #e3fcec;
            color: #388e3c;
            border: 1px solid #81c784;
        }
        .error {
            background: #ffebee;
            color: #d32f2f;
            border: 1px solid #e57373;
        }
        .back-link {
            display: inline-block;
            margin-top: 18px;
            color: #1976d2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #0d47a1;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Scholarship Application</h2>
        <div class="msg <?php echo $message_class; ?>"><?php echo htmlspecialchars($message); ?></div>
        <a class="back-link" href="dashboard.php">&larr; Back to Dashboard</a>
    </div>
</body>
</html>