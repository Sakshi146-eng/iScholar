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
            background: linear-gradient(120deg, #f7971e 0%, #ffd200 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 28px 24px 28px;
        }
        h2 {
            text-align: center;
            color: #b26a00;
            margin-bottom: 18px;
        }
        .section {
            margin-bottom: 24px;
        }
        .section h3 {
            color: #1976d2;
            margin-bottom: 10px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table th, .info-table td {
            text-align: left;
            padding: 8px 6px;
        }
        .info-table th {
            background: #ffd200;
            color: #333;
            font-weight: 600;
            width: 180px;
        }
        .info-table tr:nth-child(even) {
            background: #f7fafc;
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
        .file-link {
            color: #1976d2;
            text-decoration: underline;
            font-weight: 500;
        }
        .file-link:hover {
            color: #f7971e;
        }
    </style>
</head>
<body>
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