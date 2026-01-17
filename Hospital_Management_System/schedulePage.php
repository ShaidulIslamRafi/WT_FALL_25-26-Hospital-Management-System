<?php
   session_start();
   if(!isset($_SESSION["username"])){
    header("Location: loginPage.php");
   }

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

                   <!-- work on sidebar-->

<div class="containerone"> 
<section class="main">
    <div class="logo">
        <h2>Menu</h2>

    </div>
    <div  class="item">
        <ul> 
             <li><a href="dashboardPage.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li><a href="doctorPage.php"><i class="fas fa-user-md"></i>Doctors</a></li>
            <li><a href="schedulePage.php"><i class="fas fa-clock"></i>Schedule</a></li>
            <li><a href="appointmentPage.php"><i class="fas fa-calendar-check"></i>Appointment</a></li>
            <li><a href="patientPage.php"><i class="fas fa-procedures"></i>Patients</a></li>
            <li class="logout"><a href="logout.php"><i class="fas fa-sign-out-alt"></i>LogOut</a></li>
    </div>
</section>
</div>

                          
                   <!-- work on rightside uper-->


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
                
                <div class="user_info">
                    
                    <span>
                        <?php echo $_SESSION["username"]; ?>
                    </span>
                    <i class="fas fa-user-circle"></i>
                </div>
                
            </div>
    </div>
    <h3 class="dashname"> Dashboard </h3>
               
    </div>

                   
     


</section>

</div>


</body>
</html>