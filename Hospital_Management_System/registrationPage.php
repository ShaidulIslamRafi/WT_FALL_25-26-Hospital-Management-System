<?php
include "database.php";
$fname="";
$lname="";
$age="";
$bgroup="";
$username="";
$password="";
$cpassword="";
$error="";

function input_trim($data){
   $data=trim($data);
   return $data;

}
if($_SERVER["REQUEST_METHOD"]=="POST"){


if(empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["dob"])|| empty($_POST["age"]) || empty($_POST["bgroup"]) || empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["cpassword"]) || empty($_POST["roles"])){


$error="All field must be field";
}
else{
$fname=input_trim($_POST["fname"]);
$lname=input_trim($_POST["lname"]);
$dob=input_trim($_POST["dob"]);
$age=(int)input_trim($_POST["age"]);
$bgroup=input_trim($_POST["bgroup"]);
$username=input_trim($_POST["username"]);
$password=input_trim($_POST["password"]);
$cpassword=input_trim($_POST["cpassword"]);
$roles=input_trim($_POST["roles"]);

if(!filter_var($username,FILTER_VALIDATE_EMAIL) ){
     $error="Invslid email formate";    
}
elseif($password!==$cpassword){
     $error="Password not match.";
}
elseif($age<=0){
   $error="Age must be valid";
}
else{
   $hashpassword=password_hash($password,PASSWORD_DEFAULT);
   $hashcpassword=password_hash($cpassword,PASSWORD_DEFAULT);
   $sql="INSERT INTO admin (fname,lname,dob,age,bgroup,username,password_hash,cpassword_hash,roles)VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
   $statement=$conn->prepare($sql);
   $statement->bind_param("sssisssss",$fname,$lname,$dob,$age,$bgroup,$username,$hashpassword,$hashcpassword,$roles);

   if($statement->execute()){
      header("Location: loginPage.php");
      exit;
   }
   else{
      $error="Registration failed " . $statement->error;
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
   <meta name="description" content="Hospital Management">
   <meta name="author" content="Rafi">
   <meta name="keyword" content="Hospital,management">
   <meta name="viewport" content="width=device-width,initial-scal=1.0">
   
    <title> Registration Page</title>
     <link rel="stylesheet" href="css/registration.css" >

</head>

<body>
 <form method="post"  action="">
 <h1>Registration</h1>
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