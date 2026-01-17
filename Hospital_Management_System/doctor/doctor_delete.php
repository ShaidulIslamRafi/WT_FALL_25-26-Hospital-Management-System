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
    
   
      $statement=$conn->prepare("DELETE FROM doctor WHERE username=?");
      $statement->bind_param("s",$username);
      $statement->execute();
      $statement->close();
      
     header("Location:../doctorPage.php");
     exit;

      ?>