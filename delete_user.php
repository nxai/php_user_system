<?php
session_start();
require 'connection.php';

if(!isset($_SESSION['UserID']) || $_SESSION['Userlevel'] !== 'A') {
    header("Location: form.php");
    exit;
}

if(!isset($_GET['id'])) {
    header("Location: user_list.php");
    exit;
}

$id = $_GET['id'];

// ดึงข้อมูลผู้ใช้เพื่อลบไฟล์รูป (ถ้ามี)
$stmt = $pdo->prepare("SELECT photo FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if($user) {
    // ลบไฟล์รูปภาพถ้ามี
    if(!empty($user['photo']) && file_exists($user['photo'])) {
        unlink($user['photo']);
    }
    
    // ลบผู้ใช้จากฐานข้อมูล
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: user_list.php");
exit;
?>