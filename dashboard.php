<?php 

session_start();

if(!empty($_SESSION['lognin'])){
  echo $_SESSION['lognin'];
}else{
  header('location:login.php');
}

?>

<h4><a href="logout.php">Logout</a></h4>





