 
 <?php
 
session_start();

$conn = new mysqli('localhost','root','','userauthentication');

if(!$conn){
  echo "Database Connection Failed!!";
}

$empty_email = $empty_password = "";
 
if(isset($_POST['login'])){

    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $md5_user_password = md5($user_password);

    if(empty($user_email)){
        $empty_email = "Fill up this field";
    }
    if(empty($user_password)){
        $empty_password = "Fill up this field";
    }

    if(!empty($user_email) && !empty($user_password)){
        $sql = "SELECT * FROM users WHERE user_email = '$user_email' AND user_password = '$md5_user_password'";

        $query = $conn->query($sql);
        if($query->num_rows > 0){

            $_SESSION['lognin'] = 'login successfull';

            header('location:dashboard.php');
        }else{
            echo "Login Unsuccessfull";
        }

    }

}


?>

 
 <!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Title tag -->
    <title>Hello, world!</title>
  </head>
  <body>
    
    <div class="container">
        <div class="row">

            <div class="col-4"></div>

            <div class="col-4" style="margin-top:50px;">

            <?php if(isset($_GET['usercreated'])){ 
                echo "Information Added Successfully";
            } ?>
                <form action="login.php" method="post">

                    <div class="mt-2">
                        <lable>Email</lable>
                        <input class="form-control" type="email" name="user_email" value="<?php if(isset($_POST['login'])){ echo $user_email; } ?>">

                        <?php if(isset($_POST['login'])){ echo "<span class='text-danger'>" . $empty_email . "</span>";} ?>
                    </div>

                    <div class="mt-2">
                        <lable>Password</lable>
                        <input class="form-control" type="password" name="user_password">

                        <?php if(isset($_POST['login'])){ echo "<span class='text-danger'>" . $empty_password . "</span>";} ?>
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-success" name="login">Login</button>
                    </div>

                </form>
                <h6>Not have an Account? <a href="user.php">Register Here</a></h6>
                
            </div>

            <div class="col-4"></div>

        </div>

    </div>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
