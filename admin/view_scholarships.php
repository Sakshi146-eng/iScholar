<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$scholarships = $conn->query("SELECT * FROM scholarships ORDER BY scholarship_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Scholarships - Admin | Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #f7971e 0%, #ffd200 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 28px 24px 28px;
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
        .edit-btn {
            background: linear-gradient(90deg, #ffd200 0%, #f7971e 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 8px 22px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .edit-btn:hover {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        }
        .back-link {
            display: inline-block;
            margin-top: 18px;
            color: #b26a00;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #ff9800;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>All Scholarships</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Min GPA</th>
                <th>Max Income</th>
                <th>Category</th>
                <th>State</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $scholarships->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['min_gpa']) ?></td>
                    <td><?= htmlspecialchars($row['max_income']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['state']) ?></td>
                    <td><a href="add_requirements.php?scholarship_id=<?= $row['scholarship_id'] ?>" class="edit-btn">Edit Extra Requirements</a></td>
                </tr>
            <?php } ?>
        </table>
        <a class="back-link" href="add_scholarship.php">&larr; Back to Add Scholarship</a>
    </div>
</body>
</html> 