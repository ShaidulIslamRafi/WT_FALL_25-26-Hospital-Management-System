<?php 
    
    include "database.php";
$result=$conn->query("SELECT COUNT(*) AS total FROM appointment WHERE DATE(session)=CURDATE() ");
$row=$result->fetch_assoc();
echo $row["total"];


?>