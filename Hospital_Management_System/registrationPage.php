

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
 <label for="">Date Of Birth</label><br>
 <input type="date" name="dob"><br>
<label for="">Age:</label><br>
<input type="text" name="age"><br>


<label for="">Blood Group</label> <br>
<select name="blood" id="blood" style=" display:block;
    margin-left:100px;
    margin-bottom:0px;
    padding:8px;
    width:54%;
    border:1px solid rgba(60, 60, 65, 1);
    border-radius:10px">
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
<input type="email" name="uname"><br>
<label for="">Password:</label> <br>
<input type="text" name="password"><br>
<label for="">Confirm Password:</label> <br>
<input type="text" name="cpassword"><br>
<label for="">Select User:</label> <br>

<div class="radio_b"> 
   <label class="radio_i"> <input type="radio" name="r_admin" value="admin">Admin  </label>

   <label class="radio_i"> <input type="radio" name="r_patient">Patient  </label>

  <label class="radio_i"> <input type="radio" name="r_doctor">Doctor  </label> 

   <label class="radio_i"> <input type="radio" name="r_nurse">Nurse </label>

</div>

<input type="submit" name="submit" value="Submit" style="background-color: #2b5ec5ff;
                                              color:white;
                                              width:54%;
                                              height:35px;
                                              position:absolute;
                                              left:0%;
                                              top:86%;
                                              border-radius: 10px;"><br>
<input type="submit" name="back" value="Back" style="background-color: rgb(77, 96, 133);
                                              color:white;
                                              width:54%;
                                              height:35px;
                                              position:absolute;
                                              left:0%;
                                              top:92%;
                                              border-radius: 10px;"><br>



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