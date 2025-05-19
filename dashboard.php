<?php
session_start();
include 'db.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student = $conn->query("SELECT * FROM students WHERE student_id = $student_id")->fetch_assoc();

// Build eligible categories for the student
$student_category = strtoupper(trim($student['category']));
$eligible_categories = ["ALL"];
if ($student_category === "GEN") {
    $eligible_categories[] = "GEN";
} elseif ($student_category === "OBC") {
    $eligible_categories[] = "OBC";
    $eligible_categories[] = "GEN";
} elseif ($student_category === "SC" || $student_category === "ST") {
    $eligible_categories[] = "SC";
    $eligible_categories[] = "ST";
    $eligible_categories[] = "GEN";
}

// Fetch eligible scholarships
$sql = "SELECT * FROM scholarships 
        WHERE min_gpa <= {$student['gpa']}
        AND max_income >= {$student['income']}
        AND (";
foreach ($eligible_categories as $i => $cat) {
    if ($i > 0) $sql .= " OR ";
    $sql .= "category = '$cat'";
}
$sql .= ")
        AND (state = '{$student['state']}' OR state = 'All')
        AND last_date >= CURDATE()";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Scholarship Portal</title>
    <style>
        html {
            background: transparent !important;
        }
        body {
            background: linear-gradient(135deg, rgba(79,91,213,0.85) 0%, rgba(95,44,130,0.7) 100%), url('assets/background1.jpg') no-repeat center center fixed;
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
            max-width: 700px;
            margin: 100px auto 40px auto;
            background: rgba(255,255,255,0.85);
            border-radius: 24px;
            box-shadow: 0 4px 24px 0 rgba(31, 38, 135, 0.10);
            padding: 32px 8px 32px 8px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            color: #232946;
            margin-bottom: 8px;
            text-transform: capitalize;
        }
        .header h3 {
            color: #ff9800;
            font-weight: 500;
        }
        .scholarships {
            display: flex;
            flex-direction: column;
            gap: 24px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        .card {
            background: #f7fafc;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(31, 38, 135, 0.07);
            padding: 28px 24px 22px 24px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        .card strong {
            font-size: 1.2rem;
            color: #1976d2;
            margin-bottom: 8px;
        }
        .card p {
            color: #333;
            margin: 0 0 12px 0;
        }
        .card form {
            width: 100%;
        }
        .card input[type="submit"] {
            background: linear-gradient(90deg, #a259ff 0%, #ff6ec4 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 10px 0;
            width: 100%;
            cursor: pointer;
            transition: background 0.2s;
        }
        .card input[type="submit"]:hover {
            background: linear-gradient(90deg, #ff6ec4 0%, #a259ff 100%);
        }
        .no-scholarships {
            text-align: center;
            color: #d32f2f;
            font-size: 1.1rem;
            margin-top: 30px;
        }
        @media (max-width: 900px) {
            .container {
                max-width: 98vw;
                padding: 12px 2vw 12px 2vw;
                margin: 80px auto 24px auto;
            }
            .logout-wrapper {
                padding-right: 10px;
                margin-top: 24px;
            }
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
    </style>
</head>
<body>
    <header>
        <a href="index.html" class="header-title"><img src="assets/b5.png" alt="iScholar Logo" style="height:72px;vertical-align:middle;border-radius:12px;"></a>
    </header>
    <div class="logout-wrapper">
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    <div class="container">
        <div class="header">
            <h2>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h2>
            <h3>Eligible Scholarships</h3>
        </div>
        <div class="scholarships">
        <?php
        // Fetch applications for this student
        $app_sql = "SELECT scholarship_id, status FROM applications WHERE student_id = $student_id";
        $app_result = $conn->query($app_sql);
        $student_applications = [];
        if ($app_result) {
            while ($app_row = $app_result->fetch_assoc()) {
                $student_applications[$app_row['scholarship_id']] = $app_row['status'];
            }
        }
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sch_id = $row['scholarship_id'];
                $status = isset($student_applications[$sch_id]) ? $student_applications[$sch_id] : null;
                echo "<div class='card'>
                        <strong>" . htmlspecialchars($row['name']) . "</strong>
                        <p>" . htmlspecialchars($row['description']) . "</p>";
                if ($status) {
                    $color = $status === 'Approved' ? '#43e97b' : ($status === 'Rejected' ? '#ff5858' : '#f57c00');
                    echo "<div style='font-weight:bold;color:$color;'>Status: $status</div>";
                } else {
                    echo "<form method='POST' action='apply.php'>
                            <input type='hidden' name='scholarship_id' value='" . htmlspecialchars($row['scholarship_id']) . "'>
                            <input type='submit' value='Apply Now'>
                        </form>";
                }
                echo "</div>";
            }
        } else {
            echo "<div class='no-scholarships'>No eligible scholarships found.</div>";
        }
        ?>
        </div>
    </div>
</body>
</html>