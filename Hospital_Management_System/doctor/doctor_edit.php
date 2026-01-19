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
    
   
      $statement=$conn->prepare("SELECT username,dname,phone,specialist FROM doctor WHERE username=?");
      $statement->bind_param("s",$username);
      $statement->execute();
      $doctor=$statement->get_result()->fetch_assoc();
      $statement->close();

   if(!$doctor) 
    die("Doctor not found");
    
   if(isset($_POST["update"])){
    $dname=trim($_POST["dname"]);
    $specialist=trim($_POST["specialist"]);
    $phone=(int)trim($_POST["phone"]);

    if($dname=="" || $specialist=="" || $phone<=0){
        $error="All fileds are requred ";
    }
    else{
        $statement=$conn->prepare("UPDATE doctor SET dname=?, phone=?,specialist=? WHERE username=?");
        $statement->bind_param("siss",$dname,$phone,$specialist,$username);

        if($statement->execute()){
            header("Location:../doctorPage.php");
            exit;
        }
        else{
            $error="Update failed";
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
     <link rel="stylesheet" href="../css/doctor.css" >


</head>

<body>
    <div class="doctor"> 
  
 <div class="topbar">
    <h2>Edit Doctor</h2>
    <a class="back_btn"href="../doctorPage.php">Back</a>
 </div>
   <?php if($error!="") echo "<p class='error_msg'>$error</p>";?>
   <form method="post" class="form">

    <label> Username</label><br>
    <input type="text" value="<?php echo htmlspecialchars($doctor["username"]); ?>" disabled>  <br> 

    <label> Doctorname</label><br>
    <input type="text" name="dname" value="<?php echo htmlspecialchars($doctor["dname"]); ?>" required> <br>

    <label> Phone</label><br>
    <input type="text" name="phone" value="<?php echo htmlspecialchars($doctor["phone"]); ?>" required> <br>

    <label> Spacialist</label><br>
    <input type="text" name="specialist" value="<?php echo htmlspecialchars($doctor["specialist"]); ?>" required> <br>
     
    <button type="submit" name="update" class="btn">Update</button><br>





   </form>
</div>

</body>
</html>