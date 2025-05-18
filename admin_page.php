<?php 
session_start(); 
if(!isset($_SESSION['UserID']) || $_SESSION['Userlevel'] !== 'A') { 
    header("Location: form.php"); 
    exit; 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .welcome { margin-bottom: 20px; }
        .menu { margin: 10px 0; }
        .menu a { margin-right: 15px; text-decoration: none; }
    </style>
</head>
<body>
    <h2>Admin Page</h2>
    <div class="welcome">
        <p>Welcome <?= htmlspecialchars($_SESSION['User']) ?></p>
    </div>
    <div class="menu">
        <a href='user_list.php'>จัดการผู้ใช้</a>
        <a href='logout.php'>ออกจากระบบ</a>
    </div>
</body>
</html>