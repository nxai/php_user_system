<?php 
session_start(); 
if(!isset($_SESSION['UserID']) || $_SESSION['Userlevel'] !== 'M') { 
header("Location: form.php"); 
exit; 
} 
echo "<h2> User Page </h2>"; 
echo "<p> Welcome".$_SESSION['User']."</p>"; 
echo "<a href='user_list.php' > ຈັດການຜູ້ໃຊ້ </a> | <a href='logout.php'> ອອກລະບົບ </a>"; 
 ?>