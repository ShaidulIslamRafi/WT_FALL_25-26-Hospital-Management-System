<?php
   session_start();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> </title>
<link  rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    
<div class="containerone"> 
<section class="main">
    <div class="logo">
        <h2>Menu</h2>

    </div>
    <div  class="item">
        <ul> 
            <li><a href=""><i class="fas fa-tv"></i>Dashboard</a></li>
            <li><a href=""><i class="fas fa-user-doctor"></i>Doctors</a></li>
            <li><a href=""><i class="fas fa-clock"></i>Schedule</a></li>
            <li><a href=""><i class="fas fa-tv"></i>Appointment</a></li>
            <li><a href=""><i class="fas fa-tv"></i>Patients</a></li>
        </ul>
    </div>
</section>
</div>




<div class="containertwo"> 
<section class="inteface">
    <div class="navigation">
        <div class="n1">
            <div class="search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search">
            </div>
        </div>
            <div class="profile">
                <i class="far fa-bell"></i>
                <img src="image/dashboard.jpg" alt=""> 
                
            </div>
    </div>
    <h3 class="dashboard">
    Dashboard
    </h3>
</section>

</div>


</body>
</html>