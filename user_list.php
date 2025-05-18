<?php
session_start();
require 'connection.php';
if(!isset($_SESSION['UserID']) || $_SESSION['Userlevel'] !=='A'){

    header("Location: form.php");
    exit;
}
$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
?>
<h2>ລາຍການຜູ້ໃຊ້</h2>
<a href="create_user.php">ເພີ່ມຜູ້ໃຊ້</a> | <a href="logout.php">ອອກລະບົບ</a>
<table border="1">
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Name</th>
    <th>Level</th>
    <th>Photo</th>
    <th>Action</th>
</tr>
<?php while($row = $stmt->fetch()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= htmlspecialchars($row['username']) ?></td>
    <td><?= htmlspecialchars($row['firstname']) ?> <?= htmlspecialchars($row['lastname']) ?></td>
    <td><?= $row['userlevel'] ?></td>
    <td><img src="<?= $row['photo'] ?>" width="50px"></td>
    <td>    <a href="edit_user.php?id=<?= $row['id'] ?>">ແກ້ໄຂ</a> | 
    <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('ຕ້ອງການລຶບຜູ້ໃຊ້ນີ້ຫຼຶບໍ່?')">ລຶບ</a>
</td>
</tr>
<?php endwhile ?>
</table>