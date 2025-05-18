<?php
session_start();
require 'connection.php';
if($_SERVER["REQUEST_METHOD"]== "POST"){
    $username =$_POST['username'];
    $password =$_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])){
        $_SESSION['UserID'] = $user['id'];
        $_SESSION['User'] = $user['firstname'].' '.$user['lastname'];
        $_SESSION['Userlevel'] = $user['userlevel'];

        if($user['userlevel']==='A'){
            header("Location: admin_page.php");
        }else{
            header("Location: user_page.php");
        }

    }else{
        echo "<script>alert('ຊື່ຜູ້ໃຊ້ຫຼຶລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ'); history.back();</script>";
    }
}
?>