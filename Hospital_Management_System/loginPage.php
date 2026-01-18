<?php  
    session_start();
    include "database.php";
  $error="";
if(isset($_POST["submit"])){
  $username=trim($_POST["username"]);
  $password=$_POST["password"];

  if($username=="" || $password==""){
    $error="Username and Password are required!";
  }else{  
  
   $sql="SELECT username,password_hash,roles FROM user WHERE username=?" ;
   $statement=$conn->prepare($sql);
   if(!$statement){
    $error="Database error " . $conn->error;
   }else{
   $statement->bind_param("s",$username);
   $statement->execute();
   $result= $statement->get_result();

   if($result->num_rows == 1){
    $user = $result->fetch_assoc();
    if(password_verify($password,$user["password_hash"])){
      if($user["roles"] !== "admin" ){
        $error="Access denied! Admin only!";
      }
      else{

      






   $_SESSION["username"]=$user["username"];
   $_SESSION["roles"]=$user["roles"];

      setcookie("username",$user["username"],time()+(86400*7),"/");
  
    header("Location:dashboardPage.php");
   
   exit;
   }
    }
   else{
    $error="Invalid user password";
   }
   }else{
    $error="User Not found";
   }
   $statement->close();
   }
}
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content ="width=device-width,initial-scale=1.0">

 <title>Log In</title>
<link rel="stylesheet" href="css/login.css">

</head>

<body>
    
  <form action="" method="POST">
    <h1>Login Page</h1>
    <?php  if($error!="") echo "<p style='color:red;font-weight:bold;'>$error</p>"; ?>
    <p>Login with your details to continue </p>

   <label for="">Username:</label><br>
   <input type="text" name="username" placeholder="UserName" ><br><br><br>
   <label for="">Password:</label><br>
   <input type="password" name="password" placeholder="Password"><br><br>
   <input type="submit" name="submit" value="LogIn" style="background-color: #2b5ec5ff;color:white;width:55%;height:35px;position:absolute;left: 22%;top:60%;border-radius: 10px;" >
   <label for=""style="position:absolute;top:70%;">Don`t have an account</label>
  <a href="registrationPage.php">Sign up</a>
  </form>  




</body>



</html>