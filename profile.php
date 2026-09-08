<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT full_name, email, course, status FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Student System</title>
    <style>
        .profile-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            max-width: 600px;
        }
        .profile-card h2 { font-size: 24px; color: #333; margin-bottom: 25px; }
        .info-group {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
            font-size: 15px;
        }
        .info-label { width: 100px; font-weight: bold; color: #555; }
        .info-value { color: #333; }
        .btn-edit {
            display: inline-block;
            margin-top: 20px;
            background-color: #1e62d0;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="profile-card">
            <h2>Student Profile</h2>

            <div class="info-group">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Name:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Course:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['course']); ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Status:</span>
                <span class="info-value"><?php echo htmlspecialchars($user['status']); ?></span>
            </div>

            <a href="update-profile.php" class="btn-edit">Edit Profile</a>
        </div>
    </div>

</body>
</html>