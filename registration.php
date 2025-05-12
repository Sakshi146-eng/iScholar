<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iScholar | Student Registration</title>
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
        footer {
            width: 100%;
            background: #232946;
            color: #fff;
            text-align: center;
            padding: 18px 0 12px 0;
            font-size: 1rem;
            letter-spacing: 0.5px;
            position: static;
            left: 0;
            bottom: 0;
            z-index: 200;
            box-shadow: 0 -2px 8px rgba(26,35,126,0.10);
        }
        .container {
            max-width: 600px;
            margin: 60px auto;
            background: rgba(255,255,255,0.85);
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
        input, select {
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
        input:focus, select:focus {
            border: 1.5px solid #66a6ff;
            outline: none;
        }
        input[type="submit"] {
            width: 100%;
            max-width: 420px;
            margin-top: 10px;
        }
        @media (max-width: 700px) {
            .container {
                padding: 18px 4px 12px 4px;
            }
            form {
                padding: 0;
            }
            input, select {
                max-width: 100%;
            }
            input[type="submit"] {
                max-width: 100%;
            }
        }
        .msg {
            text-align: center;
            margin-bottom: 16px;
            color: #388e3c;
            font-weight: bold;
        }
        .error {
            color: #d32f2f;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #4f5bd5;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.html" class="header-title"><img src="assets/b5.png" alt="iScholar Logo" style="height:72px;vertical-align:middle;border-radius:12px;"></a>
    </header>
    <div class="container">
        <h2>Student Registration</h2>
        <?php
        include 'db.php';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $dob = $_POST['dob'];
            $gender = $_POST['gender'];
            $aadhaar = $_POST['aadhaar'];
            $course = $_POST['course'];
            $gpa = $_POST['gpa'];
            $year = $_POST['year'];
            $income = $_POST['income'];
            $category = $_POST['category'];
            $caste = $_POST['caste'];
            $state = $_POST['state'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO students (name, email, phone, dob, gender, aadhaar, course, gpa, year, income, category, caste, state, password)
                    VALUES ('$name', '$email', '$phone', '$dob', '$gender', '$aadhaar', '$course', '$gpa', '$year', '$income', '$category', '$caste', '$state', '$password')";
            if ($conn->query($sql) === TRUE) {
                echo '<div class="msg">Registration successful. <a href="login.php">Login here</a></div>';
            } else {
                echo '<div class="msg error">Error: ' . $conn->error . '</div>';
            }
        }
        ?>
        <form method="POST" autocomplete="off">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="date" name="dob" required>
            <select name="gender" required><option value="">Select Gender</option><option>Male</option><option>Female</option></select>
            <input type="text" name="aadhaar" placeholder="Aadhaar Number" required>
            <input type="text" name="course" placeholder="Course" required>
            <input type="text" name="gpa" placeholder="GPA/Marks" required>
            <input type="number" name="year" placeholder="Year of Study" required>
            <input type="number" name="income" placeholder="Family Income" required>
            <input type="text" name="category" placeholder="Category (GEN/OBC/SC/ST)" required>
            <input type="text" name="caste" placeholder="Caste" required>
            <input type="text" name="state" placeholder="State" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
        </form>
        <div class="login-link">Already have an account? <a href="login.php">Login</a></div>
    </div>
</body>
</html>