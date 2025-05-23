<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$app_id = isset($_GET['app_id']) ? intval($_GET['app_id']) : 0;
if ($app_id <= 0) {
    die('Invalid application ID.');
}

// Fetch application, student, and scholarship info
$sql = "SELECT a.*, s.*, sc.name AS scholarship_name, sc.description AS scholarship_desc
        FROM applications a
        JOIN students s ON a.student_id = s.student_id
        JOIN scholarships sc ON a.scholarship_id = sc.scholarship_id
        WHERE a.app_id = $app_id";
$app = $conn->query($sql)->fetch_assoc();
if (!$app) die('Application not found.');

// Fetch extra requirements and responses
$req_sql = "SELECT r.field_label, r.field_type, r.is_required, ar.value, ar.file_path
            FROM scholarship_requirements r
            LEFT JOIN application_responses ar ON ar.requirement_id = r.id AND ar.application_id = $app_id
            WHERE r.scholarship_id = {$app['scholarship_id']}";
$requirements = $conn->query($req_sql);

// Handle accept/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'] === 'accept' ? 'Approved' : 'Rejected';
    $conn->query("UPDATE applications SET status='$action' WHERE app_id=$app_id");
    // Refresh application info
    $app['status'] = $action;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details - Admin | Scholarship Portal</title>
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
        h3,strong{
           color: #232946;
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
    <div class="container">
        <h2>Application Details</h2>
        <div class="section">
            <h3>Scholarship Info</h3>
            <table class="info-table">
                <tr><th>Name</th><td><?= htmlspecialchars($app['scholarship_name']) ?></td></tr>
                <tr><th>Description</th><td><?= htmlspecialchars($app['scholarship_desc']) ?></td></tr>
            </table>
        </div>
        <div class="section">
            <h3>Student Info</h3>
            <table class="info-table">
                <tr><th>Name</th><td><?= htmlspecialchars($app['name']) ?></td></tr>
                <tr><th>Email</th><td><?= htmlspecialchars($app['email']) ?></td></tr>
                <tr><th>Phone</th><td><?= htmlspecialchars($app['phone']) ?></td></tr>
                <tr><th>DOB</th><td><?= htmlspecialchars($app['dob']) ?></td></tr>
                <tr><th>Gender</th><td><?= htmlspecialchars($app['gender']) ?></td></tr>
                <tr><th>Aadhaar</th><td><?= htmlspecialchars($app['aadhaar']) ?></td></tr>
                <tr><th>Course</th><td><?= htmlspecialchars($app['course']) ?></td></tr>
                <tr><th>GPA/Marks</th><td><?= htmlspecialchars($app['gpa']) ?></td></tr>
                <tr><th>Year of Study</th><td><?= htmlspecialchars($app['year']) ?></td></tr>
                <tr><th>Family Income</th><td><?= htmlspecialchars($app['income']) ?></td></tr>
                <tr><th>Category</th><td><?= htmlspecialchars($app['category']) ?></td></tr>
                <tr><th>Caste</th><td><?= htmlspecialchars($app['caste']) ?></td></tr>
                <tr><th>State</th><td><?= htmlspecialchars($app['state']) ?></td></tr>
            </table>
        </div>
        <div class="section">
            <h3>Extra Requirements & Responses</h3>
            <table class="info-table">
                <tr><th>Requirement</th><th>Response</th></tr>
                <?php while ($req = $requirements->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($req['field_label']) ?><?= $req['is_required'] ? ' *' : '' ?></td>
                        <td>
                            <?php if ($req['field_type'] === 'file' && $req['file_path']) { ?>
                                <a href="../<?= htmlspecialchars($req['file_path']) ?>" class="file-link" target="_blank">Download</a>
                            <?php } elseif ($req['field_type'] === 'textarea') { ?>
                                <div style="white-space: pre-line;"><?= htmlspecialchars($req['value']) ?></div>
                            <?php } else { ?>
                                <?= htmlspecialchars($req['value']) ?>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div style="text-align:center;margin-top:24px;">
            <strong>Status:</strong> <span style="color:<?= $app['status']==='Approved' ? '#43e97b' : ($app['status']==='Rejected' ? '#ff5858' : '#f57c00') ?>;font-weight:bold;"> <?= htmlspecialchars($app['status']) ?> </span>
            <?php if ($app['status'] === 'Pending') { ?>
                <form method="POST" style="display:inline;margin-left:16px;">
                    <button type="submit" name="action" value="accept" style="background:#43e97b;color:#fff;font-weight:bold;border:none;border-radius:6px;padding:8px 22px;margin-right:8px;cursor:pointer;">Accept</button>
                    <button type="submit" name="action" value="reject" style="background:#ff5858;color:#fff;font-weight:bold;border:none;border-radius:6px;padding:8px 22px;cursor:pointer;">Reject</button>
                </form>
            <?php } ?>
        </div>
        <a class="back-link" href="view_applications.php">&larr; Back to Applications</a>
    </div>
</body>
</html> 