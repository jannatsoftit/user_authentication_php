<?php

$conn = new mysqli('localhost','root','','userauthentication');

if(!$conn){
  echo "Database Connection Failed!!";
}


$empmsg_username = $empmsg_firstname = $empmsg_lastname = $empmsg_email= $empmsg_password = $empmsg_passwordreset = $empmsg_image = "";

if(isset($_POST['submit'])){

    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_first_name = mysqli_real_escape_string($conn, $_POST['user_first_name']);
    $user_last_name = mysqli_real_escape_string($conn,$_POST['user_last_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_password = mysqli_real_escape_string($conn, $_POST['user_password']);
    $user_password_reset = mysqli_real_escape_string($conn, $_POST['user_password_reset']);
    $md5_user_password = md5($user_password);

    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;


  if(empty($user_name)){
      $empmsg_username = "Fill up this field";
    }

  if(empty($user_first_name)){
       $empmsg_firstname = "Fill up this field";
	 }
	if(empty($user_last_name)){
		$empmsg_lastname = "Fill up this field";
	 }
	 if(empty($user_email)){
		$empmsg_email = "Fill up this field";
	 }
	 if(empty($user_password)){
		$empmsg_password = "Fill up this field";
	 }
	 if(empty($user_password_reset)){
		$empmsg_passwordreset = "Fill up this field";
	 }
   if(empty($image)){
		$empmsg_image = "Fill up this field";
	 }


   $query = "SELECT * FROM users WHERE user_email = '$user_email'";

   $result = mysqli_query($conn, $query);
   $information = mysqli_num_rows($result);

   if($information > 0){
    $message = 'Email Already Exsist';
   }else{

    if(!empty($user_name) && !empty($user_first_name) && !empty($user_last_name) && !empty($user_email) && !empty($user_password) && !empty($user_password_reset) && !empty($image)){
       
      if($user_password === $user_password_reset){
  
              $sql = "INSERT INTO users(user_first_name,user_last_name,user_email,user_password, user_image) 
        VALUE('$user_first_name','$user_last_name','$user_email','$md5_user_password', '$image')";
              
        if($conn->query($sql) == TRUE){
          move_uploaded_file($image_tmp_name, $image_folder);
          $messages = 'Registration successful!';
          header('location:login.php?usercreated');
        }else{
          echo "Registration Failed";
        }
  
      }
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

         

                <form action="user.php" method="post" enctype="multipart/form-data">

                <h1 style="font-family:Georgia, 'Times New Roman', Times, serif; text-align:center;">Registration Form</h1>
           
           <?php if(isset($_POST['submit'])){ echo $messages;} ?>


                <div class="mt-2">
                        <lable>User Name</lable>
                        <input class="form-control" type="text" name="user_name" value="<?php if(isset($_POST['submit'])){ echo $user_name; } ?>">
						<?php if(isset($_POST['submit'])){ echo "<span class='text-danger'>" . $empmsg_username . "</span>";} ?>
                    </div>

                <div class="mt-2">
                        <lable>First Name</lable>
                        <input class="form-control" type="text" name="user_first_name" value="<?php if(isset($_POST['submit'])){ echo $user_first_name; } ?>">
						<?php if(isset($_POST['submit'])){ echo "<span class='text-danger'>" . $empmsg_firstname . "</span>";} ?>
                    </div>

                    <div class="mt-2">
                        <lable>Last Name</lable>
                        <input class="form-control" type="text" name="user_last_name" value="<?php if(isset($_POST['submit'])){ echo $user_last_name; } ?>">
						<?php if(isset($_POST['submit'])){ echo "<span class='text-danger'>" . $empmsg_lastname . "</span>";} ?>
                    </div>
 
                    <div class="mt-2">
                        <lable>Email</lable>
                        <input class="form-control" type="email" name="user_email" value="<?php if(isset($_POST['submit'])){ echo $user_email; } ?>">
						<?php if(isset($_POST['submit'])){ echo "<span class='text-danger'>" . $empmsg_email . "</span>";} ?>
            
            <h4 style="color:red; font-size:14px;"><?php if(isset($_POST['submit'])){echo $messgage; } ?></h4>
                    </div>

                    <div class="mt-2">
                        <lable>Password</lable>
                        <input class="form-control" type="password" name="user_password">
						<?php if(isset($_POST['submit'])){ echo "<span class='text-danger'>" . $empmsg_password . "</span>";} ?>
                    </div>
                    <div class="mt-2">
                        <lable>Comfirm Password</lable>
                        <input class="form-control" type="password" name="user_password_reset" >
						<?php if(isset($_POST['submit'])){ echo "<span class='text-danger'>" . $empmsg_passwordreset . "</span>";} ?>
                    </div>

                    <div class="mt-2">
                    <lable>Upload Image</lable>
                    <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" value="<?php if (isset($_POST['submit'])) {echo $image; } ?>" />
                   <?php if (isset($_POST['submit'])) {
                   echo "<span class='text-danger'>" . $empmsg_image . "</span>";
                   } ?>

                    </div>                    

                    <div class="mt-2">
                        <button class="btn btn-success" name="submit">Submit</button>
                    </div>

                </form>
                <h6 style="font-size:18px ;" class="mt-4"> You Have an Account? <a href="login.php">Login here</a></h6>
            </div>

            <div class="col-4"></div>

        </div>

    </div>





    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>