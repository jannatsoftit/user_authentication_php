<?php 

session_start();

$conn = new mysqli('localhost','root','','userauthentication');

if(!$conn){
  echo "Database Connection Failed!!";
}

$user_id = $_SESSION['user_id'];

if(isset($_SESSION['user_email']) && isset($_SESSION['user_password'])){ 

  ?>


<!doctype html>
<html lang="en">
  <head>
    <title>Dashboard</title>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  </head>
  <body style="background-color: pink;">
      

  <div class="container" >

        <div class="col-md-12 profile mt-30" style=" text-align:center;margin-left:330px; margin-top: 200px; background-color: white; width:500px; height:500px; border-radius:10px;">
        <?php 
        
        $select = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select) > 0){
          $fetch = mysqli_fetch_assoc($select); 
        }
        if($fetch['user_image'] == ''){
           echo '<img src="images/default.jpg";>';
        }else{
            echo '<img src="uploaded_img/'.$fetch['user_image'].'">';
        }    
        ?>


       <h3>Hello, <?php echo $_SESSION['user_name'];?></h3>

        <div class="col-md-12" style="padding-top:40px; text-align:center; width:450px; height:450px;">
        <a href="update.php"><button class="btn btn-success mt-4"style="color:black; font-size:18px;">Update Profile</button></a>
        <a href="logout.php" style="font-size:18px;"><button class="btn btn-danger mt-4">Logout</button></a>
        </div>
       

        </div>
  </div>

    </body>
</html>


<?php

}else{
  header('location:login.php');
}

?>






