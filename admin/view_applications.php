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

$sql = "SELECT a.app_id, s.name AS student_name, sc.name AS scholarship_name, a.status
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
            background: linear-gradient(120deg, #f7971e 0%, #ffd200 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 900px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 24px 24px 24px;
        }
        h2 {
            text-align: center;
            color: #b26a00;
            margin-bottom: 24px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            background: #fafafa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        th, td {
            padding: 12px 10px;
            text-align: left;
        }
        th {
            background: #ffd200;
            color: #333;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f7fafc;
        }
        tr:hover {
            background: #fffde7;
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
        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #b26a00;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #ff9800;
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .container {
                padding: 12px 2vw;
            }
            table, thead, tbody, th, td, tr {
                display: block;
            }
            th, td {
                padding: 10px 6vw;
            }
            th {
                background: #ffd200;
                color: #333;
            }
            tr {
                margin-bottom: 12px;
            }
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
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['app_id']) ?></td>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><?= htmlspecialchars($row['scholarship_name']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'Pending') { ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="app_id" value="<?= $row['app_id'] ?>">
                                <button type="submit" name="action" value="accept" class="action-btn accept">Accept</button>
                                <button type="submit" name="action" value="reject" class="action-btn reject">Reject</button>
                            </form>
                        <?php } else { ?>
                            -
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <a class="back-link" href="add_scholarship.php">&larr; Back to Add Scholarship</a>
    </div>
</body>
</html>