<?php
// sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { display: flex; background-color: #f4f6f9; min-height: 100vh; }
    
    .sidebar { width: 250px; background-color: #1e62d0; color: white; padding: 20px 0; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar-header { padding: 0 20px 20px 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .sidebar-header h2 { font-size: 20px; font-weight: 600; }
    
    .sidebar-menu { list-style: none; margin-top: 15px; }
    .sidebar-menu li a { display: block; padding: 12px 20px; color: #d0e1fd; text-decoration: none; font-size: 15px; transition: 0.3s; }
    .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #154ca3; color: white; border-left: 4px solid #ffffff; }
    
    .logout-btn { background-color: #ff4d4d; color: white; display: block; padding: 12px 20px; text-decoration: none; text-align: center; font-weight: bold; margin: 20px; border-radius: 6px; transition: 0.3s; }
    .logout-btn:hover { background-color: #d93838; }

    .main-content { flex: 1; padding: 30px; }
</style>

<div class="sidebar">
    <div>
        <div class="sidebar-header">
            <h2>Student System</h2>
        </div>
        <ul class="sidebar-menu">
            <li><a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a></li>
            <li><a href="profile.php" class="<?php echo ($current_page == 'profile.php' || $current_page == 'update-profile.php') ? 'active' : ''; ?>">Profile</a></li>
            <li><a href="student.php" class="<?php echo ($current_page == 'students.php') ? 'active' : ''; ?>">Students</a></li>
            <li><a href="registration.php" class="<?php echo ($current_page == 'registration.php') ? 'active' : ''; ?>">Registration</a></li>
            <li><a href="setting.php" class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">Settings</a></li>
        </ul>
    </div>
    <div>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>