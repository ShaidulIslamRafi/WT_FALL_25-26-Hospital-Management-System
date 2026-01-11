<?php

session_start();
$SESSION=array();
if(isset($_COOKIE[sesson_name()])){
    setcookie(session_name(),'',time()+(86400*30),'/')

}

session_destroy();
?>