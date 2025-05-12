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
    <title>iScholar | Admin Login</title>
    <style>
        body {
            background: linear-gradient(135deg, rgba(79,91,213,0.85) 0%, rgba(95,44,130,0.7) 100%), url('../assets/b4.jpg') no-repeat center center fixed;
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
        footer {
            width: 100%;
            background: #232323;
            color: #fff;
            text-align: center;
            padding: 18px 0 12px 0;
            font-size: 1rem;
            letter-spacing: 0.5px;
            position: fixed;
            left: 0;
            bottom: 0;
            z-index: 200;
            box-shadow: 0 -2px 8px rgba(26,35,126,0.10);
        }
        .container {
            max-width: 400px;
            margin: 60px auto;
            background: rgba(255,255,255,0.65);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 24px 24px 24px;
            position: relative;
            z-index: 10;
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
        .msg {
            text-align: center;
            margin-bottom: 16px;
            color: #d32f2f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <header>
        <a href="../index.html" class="header-title"><img src="../assets/b5.png" alt="iScholar Logo" style="height:72px;vertical-align:middle;border-radius:12px;"></a>
    </header>
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