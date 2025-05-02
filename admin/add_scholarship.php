<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$message = '';
$message_class = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $min_gpa = $_POST['min_gpa'];
    $max_income = $_POST['max_income'];
    $category = $_POST['category'];
    $caste = $_POST['caste'];
    $state = $_POST['state'];
    $last_date = $_POST['last_date'];
    $description = $_POST['description'];

    $sql = "INSERT INTO scholarships (name, type, min_gpa, max_income, category, caste, state, last_date, description)
            VALUES ('$name', '$type', $min_gpa, $max_income, '$category', '$caste', '$state', '$last_date', '$description')";

    if ($conn->query($sql)) {
        $message = "Scholarship added successfully!";
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
    <title>Add Scholarship - Admin | Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #f7971e 0%, #ffd200 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 480px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 24px 24px 24px;
        }
        h2 {
            text-align: center;
            color: #b26a00;
            margin-bottom: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, textarea {
            margin-bottom: 14px;
            padding: 10px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
            font-size: 1rem;
            background: #f7fafc;
            transition: border 0.2s;
        }
        textarea {
            min-height: 70px;
            resize: vertical;
        }
        input:focus, textarea:focus {
            border: 1.5px solid #ffd200;
            outline: none;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #ffd200 0%, #f7971e 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        }
        .msg {
            text-align: center;
            margin-bottom: 16px;
            font-weight: bold;
            padding: 12px 10px;
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
        .view-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            color: #b26a00;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .view-link:hover {
            color: #ff9800;
            text-decoration: underline;
        }
        .logout-btn {
            display: inline-block;
            float: right;
            margin-top: -10px;
            margin-right: -10px;
            background: linear-gradient(90deg, #ff5858 0%, #f09819 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 10px 22px;
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: background 0.2s, transform 0.2s;
        }
        .logout-btn:hover {
            background: linear-gradient(90deg, #f09819 0%, #ff5858 100%);
            transform: translateY(-2px) scale(1.03);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../logout.php" class="logout-btn">Logout</a>
        <h2>Add New Scholarship</h2>
        <?php if ($message) echo '<div class="msg ' . $message_class . '">' . htmlspecialchars($message) . '</div>'; ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="name" placeholder="Scholarship Name" required>
            <input type="text" name="type" placeholder="Type (Merit/Need)" required>
            <input type="number" step="0.01" name="min_gpa" placeholder="Minimum GPA" required>
            <input type="number" step="0.01" name="max_income" placeholder="Max Family Income" required>
            <input type="text" name="category" placeholder="GEN/OBC/SC/ST/All" required>
            <input type="text" name="caste" placeholder="Caste or All" required>
            <input type="text" name="state" placeholder="State or All" required>
            <input type="date" name="last_date" required>
            <textarea name="description" placeholder="Scholarship Description" required></textarea>
            <input type="submit" value="Add Scholarship">
        </form>
        <a class="view-link" href="view_applications.php">View Applications</a>
    </div>
</body>
</html>