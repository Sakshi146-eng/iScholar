<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$scholarship_id = isset($_GET['scholarship_id']) ? intval($_GET['scholarship_id']) : 0;
if ($scholarship_id <= 0) {
    die('Invalid scholarship ID.');
}

// Handle new requirement submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field_label'], $_POST['field_type'])) {
    $label = $conn->real_escape_string($_POST['field_label']);
    $type = $conn->real_escape_string($_POST['field_type']);
    $required = isset($_POST['is_required']) ? 1 : 0;
    $conn->query("INSERT INTO scholarship_requirements (scholarship_id, field_label, field_type, is_required) VALUES ($scholarship_id, '$label', '$type', $required)");
}

// Handle delete requirement
if (isset($_GET['delete_req'])) {
    $delete_id = intval($_GET['delete_req']);
    $conn->query("DELETE FROM scholarship_requirements WHERE id = $delete_id AND scholarship_id = $scholarship_id");
    header("Location: add_requirements.php?scholarship_id=$scholarship_id");
    exit();
}

// Fetch scholarship name
$scholarship = $conn->query("SELECT name FROM scholarships WHERE scholarship_id = $scholarship_id")->fetch_assoc();

// Fetch existing requirements
$requirements = $conn->query("SELECT * FROM scholarship_requirements WHERE scholarship_id = $scholarship_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Requirements - Admin | Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #f7971e 0%, #ffd200 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 520px;
            margin: 60px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 24px 24px 24px;
        }
        h2 {
            text-align: center;
            color: #b26a00;
            margin-bottom: 18px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 24px;
        }
        input, select {
            padding: 8px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
            font-size: 1rem;
            background: #f7fafc;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #ffd200 0%, #f7971e 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: #fafafa;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 10px 8px;
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
        <h2>Add Requirements for "<?= htmlspecialchars($scholarship['name']) ?>"</h2>
        <form method="POST">
            <input type="text" name="field_label" placeholder="Field Label (e.g., Income Proof, Essay)" required>
            <select name="field_type" required>
                <option value="text">Text</option>
                <option value="textarea">Paragraph</option>
                <option value="number">Number</option>
                <option value="file">File Upload</option>
            </select>
            <label><input type="checkbox" name="is_required"> Required</label>
            <input type="submit" value="Add Requirement">
        </form>
        <h3>Existing Requirements</h3>
        <table>
            <tr>
                <th>Label</th>
                <th>Type</th>
                <th>Required</th>
                <th>Action</th>
            </tr>
            <?php while ($req = $requirements->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($req['field_label']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($req['field_type'])) ?></td>
                    <td><?= $req['is_required'] ? 'Yes' : 'No' ?></td>
                    <td><a href="add_requirements.php?scholarship_id=<?= $scholarship_id ?>&delete_req=<?= $req['id'] ?>" onclick="return confirm('Delete this requirement?');" style="color:#ff5858;font-weight:bold;">Delete</a></td>
                </tr>
            <?php } ?>
        </table>
        <a class="back-link" href="add_scholarship.php">&larr; Back to Add Scholarship</a>
    </div>
</body>
</html> 