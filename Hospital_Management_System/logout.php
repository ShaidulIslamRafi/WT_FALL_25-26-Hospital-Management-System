<?php
session_start();

$_SESSION=[];
session_destroy();

setcookie("username","",time()-3600, "/");
header("Location: loginPage.php");

exit;


?>