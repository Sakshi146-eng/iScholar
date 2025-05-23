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
            background: linear-gradient(135deg, rgba(79,91,213,0.85) 0%, rgba(95,44,130,0.7) 100%), url('../assets/b4.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .main-header {
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
        .card {
            max-width: 900px;
            margin: 60px auto;
            background: rgba(255,255,255,0.85);
            border-radius: 24px;
            box-shadow: 0 4px 24px 0 rgba(31, 38, 135, 0.10);
            padding: 40px 32px;
            position: relative;
        }
        h2 {
            text-align: center;
            color: #4f5bd5;
            margin-bottom: 24px;
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
        .edit-btn {
            background: linear-gradient(90deg, #4f5bd5 0%, #a259ff 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            padding: 10px 22px;
            text-decoration: none;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(79, 91, 213, 0.13);
            transition: all 0.3s ease;
            display: inline-block;
        }
        .edit-btn:hover {
            background: linear-gradient(90deg, #a259ff 0%, #4f5bd5 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 91, 213, 0.2);
        }
        .back-link {
            color: #4f5bd5;
            text-decoration: none;
            font-weight: 500;
            margin-top: 18px;
            display: inline-block;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(79, 91, 213, 0.1);
        }
        .back-link:hover {
            color: #a259ff;
            background: rgba(162, 89, 255, 0.1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <header class="main-header">
        <a href="../index.html" class="header-title" style="text-decoration:none;color:inherit;"><img src="../assets/b5.png" alt="iScholar Logo" style="height:72px;vertical-align:middle;border-radius:12px;"></a>
    </header>
    <div class="card">
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