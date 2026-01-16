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

if(isset($_POST["update"])){

$fname=trim($_POST["fname"]);
$lname=trim($_POST["lname"]);
$dob=trim($_POST["dob"]);
$age=(int)trim($_POST["age"]);
$bgroup=trim($_POST["bgroup"]);

$statement=&conn->prepare("UPDATE admin SET fname=?, lname=?,dob=?, age=?, bgroup=? WHERE username=?");
$statement->bind_param("sssiss",$fname,$lname,$dob,$age,$bgroup,$username);

if($statement->execute()){
    $success="Profile update successfully!";
}
else{
    $error="Update failed!";
}
$statement->close();

}
$statement=$conn->prepare("SELECT fname,lname,dob,age,bgroup,username,roles FROM admin WHERE username=?");
$statement->bind_pram("s",$username);
$statement->execute();
$user=$statement->get_result()->fetch_assoc();
$statement->close();


















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
 <?php if($error!="") echo "<p style='color:red; font-weight:bold;'>$error</p>"; ?>
<label for="">FirstName:</label> <br>
<input type="text" name="fname"><br>
<label for="">LastName:</label><br>
<input type="text" name="lname"><br>
 <label for="">Date Of Birth</label><br>
 <input type="date" name="dob"><br>
<label for="">Age:</label><br>
<input type="text" name="age"><br>


<label for="">Blood Group</label> <br>
<select name="bgroup" style=" display:block;margin-left:100px;margin-bottom:0px;padding:8px;width:54%;border:1px solid rgba(60, 60, 65, 1);border-radius:10px">
   <option value="">Blood Group</option>
  <option value="A+">A+</option>
  <option value="A-">A-</option>
  <option value="B+">B+</option>
  <option value="B-">B-</option>
  <option value="AB+">AB+</option>
  <option value="AB-">AB-</option>
  <option value="O+">O+</option>
  <option value="O-">O-</option>
</select><br>



<label for="">Username/Email:</label> <br>
<input type="email" name="username"><br>
<label for="">Password:</label> <br>
<input type="text" name="password"><br>
<label for="">Confirm Password:</label> <br>
<input type="text" name="cpassword"><br>
<label for="">Select User:</label> <br>

<div class="radio_b"> 
   <label class="radio_i"> <input type="radio" name="roles" value="admin">Admin  </label>

   <label class="radio_i"> <input type="radio" name="roles" value="patient">Patient  </label>

  <label class="radio_i"> <input type="radio" name="roles" value="doctor">Doctor  </label> 

   <label class="radio_i"> <input type="radio" name="roles" value="nurse">Nurse </label>

</div>

<input type="submit" name="submit" value="Submit" style="background-color: #2b5ec5ff;color:white;width:54%;height:35px;position:absolute;left:0%;top:86%;border-radius: 10px;"><br>
<label for=""style="position:absolute;top:93%;">I have an account</label>
  <a href="loginPage.php">Login</a>



</form>


</body>

</html>