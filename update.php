<?php

session_start();

$conn = new mysqli('localhost','root','','userauthentication');

if (!$conn) {
  echo "Database Connection Failed!!";
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$select = mysqli_query($conn, $sql) or die('query failed');
if($select->num_rows > 0){
  $fetch = mysqli_fetch_assoc($select);
}

if (isset($_POST['update_profile'])) {
  $fields = [];
  $errorMessages = [];
  $successMessage = '';

  $selectSql = "SELECT * FROM users WHERE user_id = '$user_id'";
  $selectResult = mysqli_query($conn, $selectSql);
  if (mysqli_num_rows($selectResult) > 0) {
    $fetch = mysqli_fetch_assoc($selectResult);
  }

  $updateName = mysqli_real_escape_string($conn, $_POST['update_name']);
  $updateEmail = mysqli_real_escape_string($conn, $_POST['update_email']);
  $oldPassword = mysqli_real_escape_string($conn, $_POST['old_password']);
  $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);
  $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
  $updateImage = $_FILES['update_image'];

  if (empty($updateName)) {
    $errorMessages[] = 'Name is required.';
  }
  elseif ($updateName == $fetch['user_name']) {
  }
  else {
    $fields['user_name'] = $updateName;
  }

  if (empty($updateEmail)) {
    $errorMessages[] = 'Email is required.';
  }
  elseif ($updateEmail == $fetch['user_email']) {
  }
  else {
    $fields['user_email'] = $updateEmail;
  }

  if (empty($oldPassword)) {
    $errorMessages[] = 'Old password is required.';
  }
  elseif ($fetch['user_password'] != md5($oldPassword)) {
    $errorMessages[] = 'Old password does not match.';
  }
  elseif (empty($newPassword)) {
    $errorMessages[] = 'New password is required.';
  }
  elseif (empty($confirmPassword)) {
    $errorMessages[] = 'Confirm password is required.';
  }
  elseif ($newPassword != $confirmPassword) {
    $errorMessages[] = 'New password and comfirm password do not match.';
  }
  else {
    $fields['user_password'] = md5($newPassword);
  }

  if (empty($updateImage)) {
    $errorMessages[] = 'Image is required.';
  }
  elseif ($updateImage['error'] == 4) {
  }
  elseif ($updateImage['size'] > 2000000) {
    $errorMessages[] = 'Image is too large.';
  }
  else {
    $updateImagePath = $updateImage['name'];
    move_uploaded_file($updateImage['tmp_name'], $updateImagePath);
    $fields['user_image'] = $updateImagePath;
  }

  if (!empty($fields) && empty($errorMessages)) {
    $updateSql = "UPDATE `users` SET ";
    foreach($fields as $key => $value) {
      $updateSql .= "$key = '$value'";

      if ($key != array_key_last($fields)) {
        $updateSql .= ", ";
      }
    }
    $updateSql .= " WHERE user_id = $user_id";

    mysqli_query($conn, $updateSql);
    if (mysqli_affected_rows($conn)) {
      $successMessage = 'Updated successfully.';
    }
  }


}

$selectSql = "SELECT * FROM users WHERE user_id = '$user_id'";
$selectResult = mysqli_query($conn, $selectSql);
if (mysqli_num_rows($selectResult) > 0) {
  $fetch = mysqli_fetch_assoc($selectResult);
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
    <title>Update Data</title>
  </head>
  <body>

    <div class="container">

        <div class="row">

            <div class="col-4"></div>

            <div class="col-4" style="margin-top:50px;">

            <h1 style="text-align:center; font-family:Georgia, 'Times New Roman', Times, serif">Update Profile</h1>


                <form action="" id="myform" method="post" enctype="multipart/form-data">
                  <?php

                  if ($fetch['user_image'] == '') {
                    echo '<img src="image/default.png" />';
                  }else{
                    echo '<img src="uploaded_img/'.$fetch['user_image'].'">'; 
                  }

                  if (isset($errorMessages)) {
                     foreach($errorMessages as $errorMessage){
                        echo '<div class="message">' . $errorMessage . '</div>';
                     }
                  }

                  if (isset($successMessage)) {
                    echo '<div class="message">' . $successMessage . '</div>';
                  }

                  ?>



                <div class="mt-2">
                        <lable>User Name: </lable>
                        <input class="form-control" type="text" name="update_name" value="<?php echo $fetch['user_name'];?>" />

                    </div>

                    <div class="mt-2">
                        <lable>Email: </lable>
                        <input class="form-control" type="email" name="update_email" value="<?php echo $fetch['user_email'];?> ">
                    </div>

                    <div class="mt-2">
                    <lable>Update Image: </lable>
                    <input type="file" name="update_image" value="<?php echo $image; ?>" accept="image/jpg, image/jpeg, image/png">
                    </div>

                    <div class="mt-2">
                        <lable>Old Password: </lable>
                        <input class="form-control" type="password" name="old_password" >

                    </div>

                    <div class="mt-2">
                        <lable>New Password: </lable>
                        <input class="form-control" type="password" name="new_password">

                    </div>


                    <div class="mt-2">
                        <lable>Confirm Password:</lable>
                        <input class="form-control" type="password" name="confirm_password" >

                    </div>


                </form>
                <input type="submit" form="myform" value="update profile" name="update_profile" class="btn btn-warning mt-4" />

                <!-- <a href="dashboard.php"><button class="btn btn-warning mt-4" style="color:black; font-size:18px;">Update Profile</button></a> -->

                <a href="dashboard.php"><button class="btn btn-success mt-4" style="color:black; font-size:18px;">Go Back to dashboard</button></a>
            </div>

            <div class="col-4"></div>

        </div>

    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>

</html>
