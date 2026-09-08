<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Logic ya ku-update data kwenye Database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    $course = trim($_POST['course']);

    $update_stmt = $conn->prepare("UPDATE users SET email = ?, full_name = ?, course = ? WHERE id = ?");
    $update_stmt->bind_param("sssi", $email, $full_name, $course, $user_id);

    if ($update_stmt->execute()) {
        $_SESSION['user_name'] = $full_name; // Update session name
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . $conn->error;
    }
    $update_stmt->close();
}

// Chukua data za hivi karibuni
$stmt = $conn->prepare("SELECT full_name, email, course, status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Profile - Student System</title>
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
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); max-width: 600px; }
        .card h2 { margin-bottom: 20px; color: #333; font-size: 22px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: #555; font-size: 14px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; }
        .form-group input:focus, .form-group select:focus { border-color: #1e62d0; }
        .form-group input[readonly] { background-color: #e9ecef; color: #6c757d; cursor: not-allowed; }
        .btn-container { display: flex; gap: 10px; margin-top: 20px; }
        .btn-save { background-color: #1e62d0; color: white; padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-cancel { background-color: #6c757d; color: white; padding: 10px 18px; border: none; border-radius: 6px; text-decoration: none; font-size: 14px; color: white; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <?php if (!empty($message)): ?>
            <div class="alert-success">✔ <?php echo $message; ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Update Student Profile</h2>
            
            <form action="update-profile.php" method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Course</label>
                    <select name="course">
                        <option value="Information Technology" <?php if($user['course'] == 'Information Technology') echo 'selected'; ?>>Information Technology</option>
                        <option value="Computer Science" <?php if($user['course'] == 'Computer Science') echo 'selected'; ?>>Computer Science</option>
                        <option value="Software Engineering" <?php if($user['course'] == 'Software Engineering') echo 'selected'; ?>>Software Engineering</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['status']); ?>" readonly>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn-save">Save Changes</button>
                    <a href="profile.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>