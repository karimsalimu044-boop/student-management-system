<?php
session_start();
require_once 'db.php';

// Verify that user is Logged In
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $course = trim($_POST['course']);
    $role = trim($_POST['role']);

    if (!empty($full_name) && !empty($email) && !empty($password)) {
        // Check email if is already registered in the system
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "This email is already regstrered in the system!";
        } else {
            // Hide password for securty (Password Hashing)
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, course, status, role) VALUES (?, ?, ?, ?, 'Registered', ?)");
            $stmt->bind_param("sssss", $full_name, $email, $hashed_password, $course, $role);

            if ($stmt->execute()) {
                $message = "The student has been successfully registered!";
            } else {
                $error = "An error occurrred: " . $conn->error;
            }
            $stmt->close();
        }
        $check_stmt->close();
    } else {
        $error = "Please fill in all required fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration - Student System</title>
    <style>
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            max-width: 600px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #842029;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #f5c2c7;
            max-width: 600px;
            font-size: 14px;
        }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); max-width: 600px; }
        .card h2 { margin-bottom: 20px; color: #333; font-size: 22px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: #555; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: #1e62d0; }
        .btn-register { background-color: #1e62d0; color: white; padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 14px; }
        .btn-register:hover { background-color: #154ca3; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <?php if (!empty($message)): ?>
            <div class="alert-success">✔ <?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-error">✖ <?php echo $error; ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Register New Student</h2>
            
            <form action="registration.php" method="POST">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="example@gmail.com" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter password" required>
                </div>

                <div class="form-group">
                    <label>Course</label>
                    <select name="course">
                        <option value="Information Technology">Information Technology</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Software Engineering">Software Engineering</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select name="role">
                        <option value="student">Student</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" class="btn-register">Register Student</button>
            </form>
        </div>
    </div>

</body>
</html>