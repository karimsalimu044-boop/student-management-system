<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Student System</title>
    <style>
        .card-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            max-width: 700px;
        }
        .card-section h3 { font-size: 18px; color: #333; margin-bottom: 5px; }
        .card-section p { font-size: 14px; color: #666; margin-bottom: 15px; }
        .btn {
            display: inline-block;
            background-color: #1e62d0;
            color: white;
            padding: 9px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn:hover { background-color: #154ca3; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="card-section">
            <h3>Manage Account</h3>
            <p>Manage your account information.</p>
            <a href="profile.php" class="btn">Manage Account</a>
        </div>

        <div class="card-section">
            <h3>Change Password</h3>
            <p>Change your account password.</p>
            <a href="change-password.php" class="btn">Change Password</a>
        </div>

        <div class="card-section">
            <h3>Profile</h3>
            <p>View or update your profile.</p>
            <a href="update-profile.php" class="btn">Open Profile</a>
        </div>

        <div class="card-section">
            <h3>Dashboard</h3>
            <p>Return to the main dashboard.</p>
            <a href="dashboard.php" class="btn">Back to Dashboard</a>
        </div>
    </div>

</body>
</html>