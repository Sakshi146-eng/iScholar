<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            margin: 60px auto;
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
        input, select {
            margin-bottom: 14px;
            padding: 10px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
            font-size: 1rem;
            background: #f7fafc;
            transition: border 0.2s;
        }
        input:focus, select:focus {
            border: 1.5px solid #66a6ff;
            outline: none;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #66a6ff 0%, #89f7fe 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #89f7fe 0%, #66a6ff 100%);
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
            color: #1976d2;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
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