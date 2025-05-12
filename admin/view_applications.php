<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle accept/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['app_id'], $_POST['action'])) {
    $app_id = intval($_POST['app_id']);
    $action = $_POST['action'] === 'accept' ? 'Approved' : 'Rejected';
    $conn->query("UPDATE applications SET status='$action' WHERE app_id=$app_id");
}

$sql = "SELECT a.app_id, s.name AS student_name, sc.name AS scholarship_name, a.status, s.gpa, s.income, s.category, s.caste, s.state, s.year, s.course
        FROM applications a
        JOIN students s ON a.student_id = s.student_id
        JOIN scholarships sc ON a.scholarship_id = sc.scholarship_id
        ORDER BY a.app_id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Applications - Admin | Scholarship Portal</title>
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
        .container {
            max-width: 1200px;
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
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            background: #f7fafc;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 16px;
            text-align: left;
            white-space: nowrap;
        }
        th {
            background: #ece9fc;
            color: #232946;
            font-weight: 700;
            border-bottom: 2px solid #e0e0e0;
        }
        td {
            color: #232946;
            border-bottom: 1px solid #e0e0e0;
        }
        tr:last-child td {
            border-bottom: none;
        }
        .back-link {
            color: #a259ff;
            text-decoration: none;
            font-weight: 500;
            margin-top: 18px;
            display: inline-block;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #ff6ec4;
            text-decoration: underline;
        }
        @media (max-width: 700px) {
            .container {
                padding: 18px 4px 12px 4px;
            }
            th, td {
                padding: 8px 8px;
            }
        }
        .action-btn {
            padding: 7px 18px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
            margin-right: 6px;
            transition: background 0.2s;
        }
        .accept {
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
        }
        .accept:hover {
            background: linear-gradient(90deg, #38f9d7 0%, #43e97b 100%);
        }
        .reject {
            background: linear-gradient(90deg, #ff5858 0%, #f09819 100%);
        }
        .reject:hover {
            background: linear-gradient(90deg, #f09819 0%, #ff5858 100%);
        }
        .info-link {
            color: #1976d2;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
        }
        .info-link:hover {
            color: #f7971e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Applications</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Scholarship</th>
                <th>GPA/Marks</th>
                <th>Family Income</th>
                <th>Category</th>
                <th>Caste</th>
                <th>State</th>
                <th>Year of Study</th>
                <th>Course</th>
                <th>Status</th>
                <th>Info</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['app_id']) ?></td>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><?= htmlspecialchars($row['scholarship_name']) ?></td>
                    <td><?= htmlspecialchars($row['gpa']) ?></td>
                    <td><?= htmlspecialchars($row['income']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['caste']) ?></td>
                    <td><?= htmlspecialchars($row['state']) ?></td>
                    <td><?= htmlspecialchars($row['year']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><a href="view_application_details.php?app_id=<?= $row['app_id'] ?>" class="info-link">View Details</a></td>
                </tr>
            <?php } ?>
        </table>
        <a class="back-link" href="add_scholarship.php">&larr; Back to Add Scholarship</a>
    </div>
</body>
</html>