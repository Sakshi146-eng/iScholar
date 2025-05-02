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
        body {
            background: linear-gradient(120deg, #f6d365 0%, #fda085 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 32px 24px 32px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            color: #2d3a4b;
            margin-bottom: 8px;
        }
        .header h3 {
            color: #f57c00;
            font-weight: 500;
        }
        .scholarships {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }
        .card {
            background: #f7fafc;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            padding: 24px 18px 18px 18px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
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
            background: linear-gradient(90deg, #fda085 0%, #f6d365 100%);
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
            background: linear-gradient(90deg, #f6d365 0%, #fda085 100%);
        }
        .no-scholarships {
            text-align: center;
            color: #d32f2f;
            font-size: 1.1rem;
            margin-top: 30px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 12px 2vw;
            }
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
        <a href="logout.php" class="logout-btn">Logout</a>
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