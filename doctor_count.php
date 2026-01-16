<?php
    include "database.php";
    $result=$conn->query("SELECT COUNT(*) AS total FROM admin WHERE roles='doctor'");
    $row=$result->fetch_assoc();
    echo $row['total'];

?>