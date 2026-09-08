<?php
session_start();
require_once 'db.php';

// verify that the user is Logged In
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// retrive the data of the loggeg-in student
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student System</title>
    <style>
        .welcome-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .welcome-card h1 { font-size: 24px; color: #333; margin-bottom: 8px; }
        .welcome-card p { color: #666; }
        
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-left: 5px solid #1e62d0; }
        .stat-card h3 { font-size: 14px; color: #666; margin-bottom: 5px; }
        .stat-card p { font-size: 22px; font-weight: bold; color: #333; }
    </style>
</head>
<body>

    <!-- Weka Sidebar hapa -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="welcome-card">
            <h1>Welcome again, <?php echo htmlspecialchars($user['full_name']); ?>! 👋</h1>
            <p>Here is summary of your account in the system.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Your Course</h3>
                <p><?php echo htmlspecialchars($user['course']); ?></p>
            </div>
            <div class="stat-card">
                <h3>Registration status</h3>
                <p><?php echo htmlspecialchars($user['status']); ?></p>
            </div>
            <div class="stat-card">
                <h3>Positon (Role)</h3>
                <p><?php echo ucfirst(htmlspecialchars($user['role'])); ?></p>
            </div>
        </div>
    </div>

</body>
</html>