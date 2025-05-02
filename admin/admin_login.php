<?php
session_start();
include '../db.php';

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check against database
    $sql = "SELECT * FROM admins WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = true;
            header("Location: add_scholarship.php");
            exit();
        } else {
            $login_error = 'Invalid password.';
        }
    } else {
        $login_error = 'Admin not found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #43e97b 0%, #38f9d7 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 370px;
            margin: 70px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 24px 24px 24px;
        }
        h2 {
            text-align: center;
            color: #2d3a4b;
            margin-bottom: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input {
            margin-bottom: 14px;
            padding: 10px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
            font-size: 1rem;
            background: #f7fafc;
            transition: border 0.2s;
        }
        input:focus {
            border: 1.5px solid #43e97b;
            outline: none;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
        }
        .msg {
            text-align: center;
            margin-bottom: 16px;
            color: #d32f2f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <?php if ($login_error) echo '<div class="msg">' . htmlspecialchars($login_error) . '</div>'; ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>