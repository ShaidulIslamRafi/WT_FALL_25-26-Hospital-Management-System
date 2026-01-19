<?php
   session_start();
   if(!isset($_SESSION["username"])){
    header("Location: ../loginPage.php");
    exit;
   }
   include "../database.php";


   $error="";
    if(isset($_POST["save"])){

        $username=trim($_POST["username"]);
        $dname=trim($_POST["dname"]);
        $phone=(int)trim($_POST["phone"]);
        $specialist=trim($_POST["specialist"]);
    if($username=="" || $dname=="" || $specialist=="" || $phone<=0 ){
       $error="All field are requred ";
    }
        else{
               $statement=$conn->prepare("INSERT INTO doctor (username, dname, phone, specialist) VALUES (?,?,?,?)");
               $statement->bind_param("ssis",$username,$dname,$phone,$specialist);

               if($statement->execute()){
                header("Location:../doctorPage.php");
                exit;
               }
               else{
                
                $error="Insert Failed! Username may already exist.";
               }
               $statement->close();
        }

    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> </title>
<link  rel="stylesheet" href="../css/doctor.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
      <div class="doctor"> 
    <div class="topbar">
          <h2>Add New Doctor</h2>
          <a class="back_btn"href="../doctorPage.php">Back</a>
    </div>
<?php  if($error!="") echo"<P class='error_msg'>$error</p>"; ?>

   <form method="post" class="form">

     <label >Username</label><br>
     <input type="text" name="username" required ><br>

      <label >Doctor Name</label><br>
     <input type="text" name="dname" required ><br>
     
     <label >Phone</label><br>
     <input type="text" name="phone" required ><br>

     <label >Specialist</label><br>
     <input type="text" name="specialist" required ><br>

     <button type="submit" name="save" class="btn">Save</button><br>






   </form>
 </div>

</body>
</html>