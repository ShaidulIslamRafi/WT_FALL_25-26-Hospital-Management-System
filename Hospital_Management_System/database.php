<?php
    $host="localhost";
    $user="root";
    $pass="";
    $dbname="hospital_management_system";

    $conn= new mysqli($host,$user,$pass,$dbname);
    if($conn->connect_error ){
        die("Connection Error: " .$conn->connect_error);
    }

    

?>