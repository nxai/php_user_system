<?php
session_start();
require 'connection.php';

if(!isset($_SESSION['UserID']) || $_SESSION['Userlevel'] !== 'A') {
    header("Location: form.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $userlevel = $_POST['userlevel'];
    $photo = '';

    // ตรวจสอบ username ซ้ำ
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if($stmt->fetch()) {
        echo "<script>alert('ชื่อผู้ใช้นี้มีอยู่แล้ว'); history.back();</script>";
        exit;
    }

    if(!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['photo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // ตรวจสอบว่าเป็นไฟล์ภาพจริง
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if($check === false) {
            echo "<script>alert('ไฟล์ที่อัพโหลดไม่ใช่รูปภาพ'); history.back();</script>";
            exit;
        }
        
        // อนุญาตเฉพาะบางนามสกุล
        if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "<script>alert('อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น'); history.back();</script>";
            exit;
        }
        
        // เปลี่ยนชื่อไฟล์เพื่อป้องกันการทับกัน
        $new_filename = uniqid().'.'.$imageFileType;
        $target_file = $target_dir . $new_filename;
        
        if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo = $target_file;
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการอัพโหลดไฟล์'); history.back();</script>";
            exit;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO users (username, password, firstname, lastname, userlevel, photo) VALUES (?, ?, ?, ?, ?, ?)");
    if($stmt->execute([$username, $password, $firstname, $lastname, $userlevel, $photo])) {
        echo "<script>alert('เพิ่มผู้ใช้สำเร็จ'); window.location='user_list.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด'); history.back();</script>";
    }
    exit;
}
?>

<h2>เพิ่มผู้ใช้ใหม่</h2>
<form method="post" enctype="multipart/form-data">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    ชื่อ: <input type="text" name="firstname" required><br>
    นามสกุล: <input type="text" name="lastname" required><br>
    ระดับผู้ใช้: 
    <select name="userlevel" required>
        <option value="M">สมาชิกทั่วไป (M)</option>
        <option value="A">ผู้ดูแลระบบ (A)</option>
    </select><br>
    รูปภาพ: <input type="file" name="photo"><br>
    <button type="submit">บันทึก</button>
    <a href="user_list.php">ย้อนกลับ</a>
</form>