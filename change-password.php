<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    if ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match!";
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($current_password === $user['password'] || password_verify($current_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_stmt->bind_param("si", $hashed_password, $user_id);

            if ($update_stmt->execute()) {
                $message = "Password updated successfully!";
            } else {
                $error = "An error occurred: " . $conn->error;
            }
            $update_stmt->close();
        } else {
            $error = "Current password is incorrect!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Student System</title>
    <style>
        .alert-success { background-color: #d4edda; color: #155724; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; max-width: 500px; font-size: 14px; }
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c2c7; max-width: 500px; font-size: 14px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); max-width: 500px; }
        .card h2 { margin-bottom: 20px; color: #333; font-size: 22px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: #555; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 14px; outline: none; }
        .form-group input:focus { border-color: #1e62d0; }
        .btn-container { display: flex; gap: 10px; margin-top: 20px; }
        .btn-save { background-color: #1e62d0; color: white; padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-cancel { background-color: #6c757d; color: white; padding: 10px 18px; border: none; border-radius: 6px; text-decoration: none; font-size: 14px; }
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
            <h2>Change Password</h2>
            
            <form action="change-password.php" method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <input type="password" name="current_password" required>
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn-save">Update Password</button>
                    <a href="settings.php" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>