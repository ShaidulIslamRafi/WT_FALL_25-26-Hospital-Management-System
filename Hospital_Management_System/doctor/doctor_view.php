<?php
session_start();
   if(!isset($_SESSION["username"])){
    header("Location: ../loginPage.php");
    exit;
   }
   include "../database.php";
   $error="";

   $username = $_GET["username"] ?? "";
   if($username=="") die("Missing username");
    
   
      $statement=$conn->prepare("SELECT username,dname,phone,specialist,created_at FROM doctor WHERE username=?");
      $statement->bind_param("s",$username);
      $statement->execute();
      $doctor=$statement->get_result()->fetch_assoc();
      $statement->close();
      
     if(!$doctor) 
    die("Doctor not found");

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
     <link rel="stylesheet" href="../css/doctor.css" >


</head>

<body>
<div class="doctor">  
<div class="topbar">
    <h2>Doctor Details</h2>
    <a class="back_btn"href="../doctorPage.php">Back</a>
 </div>

<div class="card">
   <p><b>Doctor Name:</b> <?php echo htmlspecialchars($doctor["dname"]); ?></p>   
   <p><b>Username:</b> <?php echo htmlspecialchars($doctor["username"]); ?></p>  
   <p><b>Phone:</b> <?php echo htmlspecialchars($doctor["phone"]); ?></p>  
   <p><b>Specialist:</b> <?php echo htmlspecialchars($doctor["specialist"]); ?></p>  
   <p><b>Created at</b> <?php echo htmlspecialchars($doctor["created_at"]); ?></p>  

</div>

</div> 


</body>
</html>