<?php
session_start();
require_once 'db.php';

// Verfy that the user is Logged In
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = "";

// Logic for Delete Student
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    // Prevent the user from deleting their own account while logged in
    if ($delete_id == $_SESSION['user_id']) {
        $message = "You cannot delete your own account while logged!";
    } else {
        $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $delete_stmt->bind_param("i", $delete_id);
        
        if ($delete_stmt->execute()) {
            $message = "The student has been succefully deleted!";
        } else {
            $message = "An error occured while deleting: " . $conn->error;
        }
        $delete_stmt->close();
    }
}

//Retrive the list for all student from the Database
$query = "SELECT id, full_name, email, course, status, role, created_at FROM users ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Students - Student System</title>
    <style>
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #bee5eb;
            font-size: 14px;
        }
        .table-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .table-header h2 { font-size: 22px; color: #333; }
        .btn-add {
            background-color: #1e62d0;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: bold;
        }
        tr:hover { background-color: #f1f1f1; }

        .status-badge {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-delete {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
            margin-left: 10px;
        }
        .btn-delete:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        
        <?php if (!empty($message)): ?>
            <div class="alert-info">ℹ <?php echo $message; ?></div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-header">
                <h2>List of Students</h2>
                <a href="registration.php" class="btn-add">+ New Registration</a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Full name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th>Actions</th>
        </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['course']); ?></td>
                                <td><span class="status-badge"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                <td><?php echo ucfirst(htmlspecialchars($row['role'])); ?></td>
                                <td>
                                    <a href="students.php?delete_id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('You want to delete this student?');">delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">No student was found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>