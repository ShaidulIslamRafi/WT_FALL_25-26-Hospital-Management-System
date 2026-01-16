<?php
    session_start();
    include "database.php";
  $error="";
if(isset($_POST["submit"])){
  $username=$_POST["username"];
  $password=$_POST["password"];
  
   $sql="SELECT * FROM admin WHERE `Username/Email`=?  AND `Password`=?" ;
   $statement=$conn->prepare($sql);
   $statement->bind_param("ss",$username,$password);
   $statement->execute();
   $result= $statement->get_result();

   if($result->num_rows == 1){

   $_SESSION["username"]=$username;


      setcookie("username",$username,time()+(86400*7),"/");
  
    header("Location:dashboard.php");
   
   exit;
   }
   else{
    $error="Invalid user password";
   }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="description" content="Hospital management">
  <meta name="author" content="Rafi">
  <meta name="keyword" content="Hospital,manangement">
  <meta name="viewpoint" content ="width=device-width,initial-scal=1.0">

 <title>Log In</title>
<link rel="stylesheet" href="css/login.css">

</head>

<body>
    
  <form action="" method="POST">
    <h1>Login Page</h1>
    <p>Login with your details to continue </p>

   <label for="">Username:</label><br>
   <input type="text" name="username" placeholder="UserName" ><br><br><br>
   <label for="">Password:</label><br>
   <input type="text " name="password" placeholder="Password"><br><br>
   <input type="submit" name="submit" value="LogIn" style="background-color: #2b5ec5ff;color:white;width:55%;height:35px;position:absolute;left: 22%;top:60%;border-radius: 10px;" >
   <label for=""style="position:absolute;top:70%;">Don`t have an account</label>
  <a href="registrationPage.php">Sign up</a>
  </form>  




</body>



</html>