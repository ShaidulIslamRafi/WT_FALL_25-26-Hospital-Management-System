<?php 
    
    include "database.php";
$result=$conn->query("SELECT COUNT(*) AS total FROM appointment ");
$row=$result->fetch_assoc();
echo $row["total"];


?>