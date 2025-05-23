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
    <title>iScholar | Student Login</title>
    <style>
        body {
            background: linear-gradient(135deg, rgba(79,91,213,0.85) 0%, rgba(95,44,130,0.7) 100%), url('assets/b3.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        body::before {
            content: none !important;
            display: none !important;
        }
        header {
            width: 100%;
            background: transparent;
            color: #fff;
            padding: 22px 0 16px 40px;
            text-align: left;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: none;
            z-index: 200;
            position: relative;
        }
        .header-title {
            text-decoration: none;
            color: inherit;
        }
        .header-actions {
            display: none !important;
        }
        .container {
            max-width: 600px;
            margin: 60px auto;
            background: rgba(255,255,255,0.65);
            border-radius: 24px;
            box-shadow: 0 4px 24px 0 rgba(31, 38, 135, 0.10);
            padding: 40px 32px 32px 32px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 {
            text-align: center;
            color: #232946;
            margin-bottom: 24px;
        }
        form {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input {
            margin-bottom: 14px;
            padding: 12px 14px;
            border: 1px solid #b0bec5;
            border-radius: 8px;
            font-size: 1rem;
            background: #f7fafc;
            transition: border 0.2s;
            width: 100%;
            max-width: 420px;
        }
        input:focus {
            border: 1.5px solid #66a6ff;
            outline: none;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #a259ff 0%, #ff6ec4 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #ff6ec4 0%, #a259ff 100%);
            color: #fff;
        }
        @media (max-width: 700px) {
            .container {
                padding: 18px 4px 12px 4px;
            }
            form {
                padding: 0;
            }
            input {
                max-width: 100%;
            }
            input[type="submit"] {
                max-width: 100%;
            }
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
    <header>
        <a href="index.html" class="header-title"><img src="assets/b5.png" alt="iScholar Logo" style="height:72px;vertical-align:middle;border-radius:12px;"></a>
    </header>
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