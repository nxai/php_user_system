<?php
require 'connection.php'; //ເຊື່ອມຕໍ່ DB
if($_SERVER["REQUEST_METHOD"]== "POST"){
    $username = $_POST['username'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $userlevel = 'M';
    $photo= '';

    if(!empty($_FILES['photo']['name'])){
        $target = "uploads/" .basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $target);
        $photo = $target;
    }
    $stmt = $pdo->prepare("INSERT INTO users (username,password, firstname, lastname, userlevel, photo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$username, $password, $firstname, $lastname, $userlevel, $photo]);
    echo "<script>alert('ສະຊະມາຊິກສຳເລັດ'); window.location='form.php';</script>";
}
?>

<form action="" method="post" enctype="multipart/form-data">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Firstname: <input type="text" name="firstname" required><br>
    Lastname: <input type="text" name="lastname" required><br>
    Photo: <input type="file" name="photo"><br>
    <button type="submit">Register</button>
</form>