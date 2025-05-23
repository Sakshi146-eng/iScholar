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
        $new_id = $conn->insert_id;
        header("Location: add_requirements.php?scholarship_id=$new_id");
        exit();
    } else {
        $message = "Error: " . $conn->error;
        $message_class = 'error';
    }
}

$pending_count = 0;
$pending_result = $conn->query("SELECT COUNT(*) as cnt FROM applications WHERE status = 'Pending'");
if ($pending_result && $row = $pending_result->fetch_assoc()) {
    $pending_count = (int)$row['cnt'];
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
            background: linear-gradient(135deg, rgba(79,91,213,0.85) 0%, rgba(95,44,130,0.7) 100%), url('../assets/b4.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .logout-wrapper {
            width: 99%;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-top: -44px;
            margin-bottom: -40px;
            padding-right: 40px;
            z-index: 30;
        }
        
        .container {
            max-width: 500px;
            margin: 60px auto;
            background: rgba(255,255,255,0.75);
            border-radius: 24px;
            box-shadow: 0 4px 24px 0 rgba(31, 38, 135, 0.10);
            padding: 40px 32px 32px 32px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .header {
            width: 100%;
            background: transparent;
            color: #fff;
            padding: 22px 0 16px 40px;
            text-align: left;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 200;
            flex-shrink: 0;
        }
        .top-btns {
            display: flex;
            flex-direction: column;
            gap: 18px;
            align-items: center;
            margin-bottom: 18px;
        }
        h2 {
            text-align: center;
            color: #4f5bd5;
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
            background: linear-gradient(90deg, #a259ff 0%, #ff6ec4 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            padding: 10px 0;
            font-size: 1rem;
            transition: background 0.2s, color 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #ff6ec4 0%, #a259ff 100%);
            color: #fff;
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
        
        
        .logout-btn {
            background: linear-gradient(90deg, #a259ff 0%, #ff6ec4 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 10px 22px;
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(162,89,255,0.13);
            transition: background 0.2s, transform 0.2s;
        }
        .logout-btn:hover {
            background: linear-gradient(90deg, #ff6ec4 0%, #a259ff 100%);
            transform: translateY(-2px) scale(1.03);
        }
        .view-link {
            display: block;
            text-align: center;
            margin: 32px auto 0 auto;
            background: linear-gradient(90deg, #1976d2 0%, #00b4d8 100%);
            color: #fff !important;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 12px 32px;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(25, 118, 210, 0.13);
            transition: background 0.2s, color 0.2s;
            width: fit-content;
            text-decoration: none;
            position: static;
        }
        .view-link:hover {
            background: linear-gradient(90deg, #00b4d8 0%, #1976d2 100%);
            color: #ffd200 !important;
        }
        .notif-badge {
            display: inline-block;
            background: #ff5858;
            color: #fff;
            font-weight: bold;
            border-radius: 50%;
            padding: 3px 10px;
            margin-left: 8px;
            font-size: 1rem;
            vertical-align: middle;
            box-shadow: 0 0 6px #ff5858cc;
            animation: notif-pop 0.7s cubic-bezier(.68,-0.55,.27,1.55) 1;
        }
        @keyframes notif-pop {
            0% { transform: scale(0.5); opacity: 0.5; }
            70% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }
        .view-btn {
            background: linear-gradient(90deg, #a259ff 0%, #ff6ec4 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 10px 22px;
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(162,89,255,0.13);
            transition: background 0.2s, transform 0.2s;
            display: inline-block;
        }
        .view-btn:hover {
            background: linear-gradient(90deg, #ff6ec4 0%, #a259ff 100%);
            color: #fff;
            transform: translateY(-2px) scale(1.03);
        }
    </style>
</head>
<body>
    <header>
        <a href="../index.html" class="header-title" style="text-decoration:none;color:inherit;"><img src="../assets/b5.png" alt="iScholar Logo" style="height:72px;vertical-align:middle;border-radius:12px;"></a>
    </header>
    <div class="logout-wrapper">
        <a href="../logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="container">
        <a href="view_scholarships.php" class="view-btn">View All Scholarships</a>
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
        <a class="view-link" href="view_applications.php">
            View Applications
            <?php if ($pending_count > 0) { ?>
                <span class="notif-badge"><?php echo $pending_count; ?></span>
            <?php } ?>
        </a>
    </div>
</body>
</html>