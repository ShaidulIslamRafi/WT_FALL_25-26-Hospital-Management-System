<?php
session_start();
   if(!isset($_SESSION["username"])){
    header("Location: loginPage.php");
    exit;
   }
include "database.php";
$username=$_SESSION["username"];
$success="";
$error="";

      $statement=$conn->prepare("SELECT fname,lname,dob,age,password_hash FROM user WHERE username=?");
      $statement->bind_param("s",$username);
      $statement->execute();
      $user=$statement->get_result()->fetch_assoc();
      $statement->close();

if(isset($_POST["submit"])){

$fname=trim($_POST["fname"]);
$lname=trim($_POST["lname"]);
$dob=trim($_POST["dob"]);
$age=(int)trim($_POST["age"]);

if(!empty($_POST["current_password"])){
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

   if($new_password !== $confirm_password){
      $error="New Password and confirm password do not match!";
   }
   else if(!password_verify($current_password,$user["password_hash"])){
       $error="Current Password is incorrect!";
   }
    else{
      $new_hash=password_hash($new_password,PASSWORD_DEFAULT);
      $statement=$conn->prepare("UPDATE user SET password_hash=? WHERE username=?");
      $statement->bind_param("ss",$new_hash,$username);
      $statement->execute();
      $statement->close();

      $success="password change successfully!";

    }
}



if($error ==""){
   $statement=$conn->prepare("UPDATE user SET fname=?, lname=?,dob=?, age=? WHERE username=?");
   $statement->bind_param("sssis",$fname,$lname,$dob,$age,$username);


  if($statement->execute()){
     if($success =="" )
      $success="Profile update successfully!";
}
else{
    $error="Update failed!";
  }
  
   $statement->close();
}
 }


?>





<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <meta name="description" content="Hospital Management">
   <meta name="author" content="Rafi">
   <meta name="keyword" content="Hospital,management">
   <meta name="viewport" content="width=device-width,initial-scal=1.0">
   
    <title> Registration Page</title>
     <link rel="stylesheet" href="css/updateProfile.css" >

</head>

<body>

 <form method="post"  action="">
 <h1>Update Profile</h1>
 <div class="message">
<?php if($success!="") echo "<p style='color:green; font-weight:bold;'>$success</p>"; ?>
 <?php if($error!="") echo "<p style='color:red; font-weight:bold;'>$error</p>"; ?>
 </div>
 
<label for="">FirstName:</label> <br>
<input type="text" name="fname" value="<?php echo htmlspecialchars($user['fname']);?>"required><br>
<label for="">LastName:</label><br>
<input type="text" name="lname" value="<?php echo htmlspecialchars($user['lname']);?>"required><br>
 <label for="">Date Of Birth</label><br>
 <input type="date" name="dob" value="<?php echo htmlspecialchars($user['dob']);?>"required><br>
<label for="">Age:</label><br>
<input type="text" name="age" value="<?php echo (int) $user['age'];?>"required><br>

<label>Current Password:</label><br>
<input type="password" name="current_password"><br>
<label>New Password:</label><br>
<input type="password" name="new_password"><br>
<label>Confirm New Password:</label><br>
<input type="password" name="confirm_password"><br>




<input type="submit" name="submit" value="Update" ><br>
</form>
<br> <br>
<a href="dashboardPage.php" class="back_btn" >Back To DashBoard</a>


</body>

</html>