

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
<label for="">FirstName:</label> <br>
<input type="text" name="fname"><br>
<label for="">LastName:</label><br>
<input type="text" name="lname"><br>
<label for="">Age:</label><br>
<input type="text" name="age"><br>
<label for="">Blood Group:</label> <br>
<input type="text" name="bgroup"> <br>
<label for="">UserName/Email:</label> <br>
<input type="email" name="uname"><br>
<label for="">Password:</label> <br>
<input type="text" name="password"><br>
<label for="">Confirm Password:</label> <br>
<input type="text" name="cpassword"><br>
<input type="submit" name="submit" value="Submit"><br>



</form>

<?php

$fname="";
$lname="";
$age="";
$bgroup="";
$uname="";
$password="";
$cpassword="";
$error="";

function input_trim($data){
   $data=trim($data);
   return $data;

}

if(empty($_POST["fname"]) || empty($_POST["lname"]) || empty($_POST["age"]) || empty($_POST["bgroup"]) || empty($_POST["uname"]) || empty($_POST["password"]) || empty($_POST["cpassword"])){


$error="All field must be field";
}
else{
$fname=input_trim($_POST["fname"]);
$lname=input_trim($_POST["lname"]);
$age=input_trim($_POST["age"]);
$bgroup=input_trim($_POST["bgroup"]);
$uname=input_trim($_POST["uname"]);
$password=input_trim($_POST["password"]);
$cpassword=input_trim($_POST["cpassword"]);

}



?>



</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"]=="POST" && empty($error)){

    echo $fname; 
    echo $lname;


}


?>