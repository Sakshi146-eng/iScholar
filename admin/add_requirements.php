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
    // Delete related application_responses first
    $conn->query("DELETE FROM application_responses WHERE requirement_id = $delete_id");
    // Now delete the requirement
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
        h2 {
            text-align: center;
            color: #4f5bd5;
            margin-bottom: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
            margin-bottom: 24px;
        }
        input, select {
            margin-bottom: 14px;
            padding: 10px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
            font-size: 1rem;
            background: #f7fafc;
            transition: border 0.2s;
        }
        input:focus, select:focus {
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
        .back-link:hover {
            background: linear-gradient(90deg, #00b4d8 0%, #1976d2 100%);
            color: #ffd200 !important;
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