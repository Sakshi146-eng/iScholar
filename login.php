<?php
session_start();
include 'db.php';

$login_error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM students WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['student_id'] = $user['student_id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $login_error = 'Invalid password.';
        }
    } else {
        $login_error = 'User not found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #f093fb 0%, #f5576c 100%);
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
            border: 1.5px solid #f093fb;
            outline: none;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #f5576c 0%, #f093fb 100%);
        }
        .msg {
            text-align: center;
            margin-bottom: 16px;
            color: #d32f2f;
            font-weight: bold;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
        .register-link a {
            color: #1976d2;
            text-decoration: none;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <?php if ($login_error) echo '<div class="msg">' . htmlspecialchars($login_error) . '</div>'; ?>
        <form method="POST" autocomplete="off">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <div class="register-link">Don't have an account? <a href="registration.php">Register</a></div>
    </div>
</body>
</html>