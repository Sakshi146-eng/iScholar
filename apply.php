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
        html {
            background: transparent !important;
        }
        body {
            background: linear-gradient(135deg, rgba(79,91,213,0.85) 0%, rgba(95,44,130,0.7) 100%), url('assets/background1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .container {
            max-width: 700px;
            margin: 100px auto 40px auto;
            background: rgba(255,255,255,0.85);
            border-radius: 24px;
            box-shadow: 0 4px 24px 0 rgba(31, 38, 135, 0.10);
            padding: 32px 24px;
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
        }
        h2 {
            color: #232946;
            margin-bottom: 18px;
            text-align: center;
        }
        .msg {
            font-size: 1.1rem;
            font-weight: bold;
            margin: 24px 0 10px 0;
            padding: 16px 10px;
            border-radius: 8px;
            width: 100%;
            text-align: center;
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
            width: 100%;
            max-width: 100%;
        }
        label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
            color: #232946;
        }
        input, textarea, select {
            width: 100%;
            margin-bottom: 14px;
            padding: 12px;
            border: 1px solid #b0bec5;
            border-radius: 8px;
            font-size: 1rem;
            background: #f7fafc;
            box-sizing: border-box;
        }
        input[type="file"] {
            padding: 8px;
            background: #fff;
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
            transform: translateY(-2px) scale(1.03);
        }
        input[type="submit"]::after {
            content: attr(value);
            display: block;
            background: linear-gradient(90deg, #1976d2 0%, #00c6fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            font-weight: bold;
            font-size: 1.1rem;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 2;
        }
        
        @media (max-width: 900px) {
            .container {
                max-width: 98vw;
                padding: 24px 16px;
                margin: 80px auto 24px auto;
            }
        }
    </style>
</head>
<body>
    <header>
        <a href="index.html" class="header-title"><img src="assets/b5.png" alt="iScholar Logo" style="height:72px;vertical-align:middle;border-radius:12px;"></a>
    </header>
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