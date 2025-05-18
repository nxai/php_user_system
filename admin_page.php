<?php 
session_start(); 
if(!isset($_SESSION['UserID']) || $_SESSION['Userlevel'] !== 'A') { 
header("Location: form.php"); 
exit; 
} 
echo "<h2> Admin Page </h2>"; 
echo "<p> Welcome".$_SESSION['User']."</p>"; 
echo "<a href='user_list.php' > ຈັດການຜູ້ໃຊ້ </a> | <a href='logout.php'> ອອກລະບົບ </a>"; 
 ?>