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

// ดึงข้อมูลผู้ใช้เดิม
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if(!$user) {
    header("Location: user_list.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $userlevel = $_POST['userlevel'];
    $photo = $user['photo']; // ค่าเดิม
    
    // ตรวจสอบ username ซ้ำ (ไม่นับตัวเอง)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->execute([$username, $id]);
    if($stmt->fetch()) {
        echo "<script>alert('ชื่อผู้ใช้นี้มีอยู่แล้ว'); history.back();</script>";
        exit;
    }

    // ตรวจสอบการอัพเดทรหัสผ่าน
    $password = $user['password'];
    if(!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    // อัพโหลดรูปใหม่ (ถ้ามี)
    if(!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['photo']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if($check === false) {
            echo "<script>alert('ไฟล์ที่อัพโหลดไม่ใช่รูปภาพ'); history.back();</script>";
            exit;
        }
        
        if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "<script>alert('อนุญาตเฉพาะไฟล์ JPG, JPEG, PNG & GIF เท่านั้น'); history.back();</script>";
            exit;
        }
        
        $new_filename = uniqid().'.'.$imageFileType;
        $target_file = $target_dir . $new_filename;
        
        if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            // ลบไฟล์เก่าถ้ามี
            if(!empty($user['photo']) && file_exists($user['photo'])) {
                unlink($user['photo']);
            }
            $photo = $target_file;
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการอัพโหลดไฟล์'); history.back();</script>";
            exit;
        }
    }

    $stmt = $pdo->prepare("UPDATE users SET username=?, password=?, firstname=?, lastname=?, userlevel=?, photo=? WHERE id=?");
    if($stmt->execute([$username, $password, $firstname, $lastname, $userlevel, $photo, $id])) {
        echo "<script>alert('อัพเดทข้อมูลสำเร็จ'); window.location='user_list.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด'); history.back();</script>";
    }
    exit;
}
?>

<h2>แก้ไขผู้ใช้</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    
    Username: <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>
    รหัสผ่านใหม่ (เว้นว่างถ้าไม่ต้องการเปลี่ยน): <input type="password" name="password"><br>
    ชื่อ: <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" required><br>
    นามสกุล: <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required><br>
    ระดับผู้ใช้: 
    <select name="userlevel" required>
        <option value="M" <?= $user['userlevel'] == 'M' ? 'selected' : '' ?>>สมาชิกทั่วไป (M)</option>
        <option value="A" <?= $user['userlevel'] == 'A' ? 'selected' : '' ?>>ผู้ดูแลระบบ (A)</option>
    </select><br>
    รูปภาพปัจจุบัน: 
    <?php if(!empty($user['photo'])): ?>
        <img src="<?= $user['photo'] ?>" width="50"><br>
    <?php else: ?>
        ไม่มีรูปภาพ<br>
    <?php endif; ?>
    เปลี่ยนรูปภาพ: <input type="file" name="photo"><br>
    
    <button type="submit">บันทึกการเปลี่ยนแปลง</button>
    <a href="user_list.php">ย้อนกลับ</a>
</form>