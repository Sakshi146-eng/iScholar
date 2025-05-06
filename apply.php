<?php
session_start();
include 'db.php';

$message = '';
$message_class = '';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$scholarship_id = $_POST['scholarship_id'];

// Fetch extra requirements for this scholarship
$requirements = $conn->query("SELECT * FROM scholarship_requirements WHERE scholarship_id = $scholarship_id");
$requirements_arr = [];
while ($row = $requirements->fetch_assoc()) {
    $requirements_arr[] = $row;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_submit'])) {
    // Check if already applied
    $check = $conn->query("SELECT * FROM applications WHERE student_id = $student_id AND scholarship_id = $scholarship_id");
    if ($check->num_rows > 0) {
        $message = "You've already applied for this scholarship.";
        $message_class = 'error';
    } else {
        // Insert application
        $sql = "INSERT INTO applications (student_id, scholarship_id) VALUES ($student_id, $scholarship_id)";
        if ($conn->query($sql)) {
            $application_id = $conn->insert_id;
            // Save extra responses
            foreach ($requirements_arr as $req) {
                $req_id = $req['id'];
                $type = $req['field_type'];
                $value = NULL;
                $file_path = NULL;
                if ($type === 'file') {
                    if (isset($_FILES['requirement_' . $req_id]) && $_FILES['requirement_' . $req_id]['error'] === UPLOAD_ERR_OK) {
                        $upload_dir = 'uploads/';
                        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                        $filename = uniqid('file_') . '_' . basename($_FILES['requirement_' . $req_id]['name']);
                        $target = $upload_dir . $filename;
                        if (move_uploaded_file($_FILES['requirement_' . $req_id]['tmp_name'], $target)) {
                            $file_path = $target;
                        }
                    }
                } else {
                    $value = isset($_POST['requirement_' . $req_id]) ? $conn->real_escape_string($_POST['requirement_' . $req_id]) : NULL;
                }
                $conn->query("INSERT INTO application_responses (application_id, requirement_id, value, file_path) VALUES ($application_id, $req_id, " . ($value ? "'$value'" : "NULL") . ", " . ($file_path ? "'$file_path'" : "NULL") . ")");
            }
            $message = "Application submitted successfully!";
            $message_class = 'success';
        } else {
            $message = "Error: " . $conn->error;
            $message_class = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Application - Scholarship Portal</title>
    <style>
        body {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            margin: 80px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 32px 24px 24px 24px;
            text-align: center;
        }
        h2 {
            color: #1976d2;
            margin-bottom: 18px;
        }
        .msg {
            font-size: 1.1rem;
            font-weight: bold;
            margin: 24px 0 10px 0;
            padding: 16px 10px;
            border-radius: 8px;
        }
        .success {
            background: #e3fcec;
            color: #388e3c;
            border: 1px solid #81c784;
        }
        .error {
            background: #ffebee;
            color: #d32f2f;
            border: 1px solid #e57373;
        }
        .back-link {
            display: inline-block;
            margin-top: 18px;
            color: #1976d2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .back-link:hover {
            color: #0d47a1;
            text-decoration: underline;
        }
        form {
            text-align: left;
            margin-top: 20px;
        }
        label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
        }
        input, textarea, select {
            width: 100%;
            margin-bottom: 14px;
            padding: 8px;
            border: 1px solid #b0bec5;
            border-radius: 6px;
            font-size: 1rem;
            background: #f7fafc;
        }
        input[type="file"] {
            padding: 0;
        }
        input[type="submit"] {
            background: linear-gradient(90deg, #1976d2 0%, #64b5f6 100%);
            color: #fff;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #64b5f6 0%, #1976d2 100%);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Scholarship Application</h2>
        <div class="msg <?php echo $message_class; ?>"><?php echo htmlspecialchars($message); ?></div>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="scholarship_id" value="<?php echo htmlspecialchars($scholarship_id); ?>">
            <?php foreach ($requirements_arr as $req) { ?>
                <label><?php echo htmlspecialchars($req['field_label']); ?><?php if ($req['is_required']) echo ' *'; ?></label>
                <?php if ($req['field_type'] === 'text') { ?>
                    <input type="text" name="requirement_<?php echo $req['id']; ?>" <?php if ($req['is_required']) echo 'required'; ?>>
                <?php } elseif ($req['field_type'] === 'textarea') { ?>
                    <textarea name="requirement_<?php echo $req['id']; ?>" <?php if ($req['is_required']) echo 'required'; ?>></textarea>
                <?php } elseif ($req['field_type'] === 'number') { ?>
                    <input type="number" name="requirement_<?php echo $req['id']; ?>" <?php if ($req['is_required']) echo 'required'; ?>>
                <?php } elseif ($req['field_type'] === 'file') { ?>
                    <input type="file" name="requirement_<?php echo $req['id']; ?>" <?php if ($req['is_required']) echo 'required'; ?>>
                <?php } ?>
            <?php } ?>
            <input type="submit" name="apply_submit" value="Submit Application">
        </form>
        <a class="back-link" href="dashboard.php">&larr; Back to Dashboard</a>
    </div>
</body>
</html>