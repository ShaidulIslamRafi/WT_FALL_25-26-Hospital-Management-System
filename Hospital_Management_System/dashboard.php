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

                   <!-- work on sidebar-->

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
                <img src="image/dashboard.jpg" alt=""> 
                
            </div>
    </div>
    <h3 class="dashname"> Dashboard </h3>
               <!-- work on rightside middle-->
    <div class="value">
        <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3>22</h3>
                <span>Doctors</span>
            </div>
        </div>
         <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3>22</h3>
                <span>Patients</span>
            </div>
        </div>
         <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3>22</h3>
                <span>NewBooking</span>
            </div>
        </div>
         <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3>22</h3>
                <span>Today</span>
            </div>
        </div>
    </div>

                       <!-- work on rightside lower-->
       <div class="board">
        <table width="100%">
            <thead>
                <tr>
                    <td>Name</td>
                    <td>Title</td>
                    <td>Status</td>
                    <td>Role</td>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td> </td>
                </tr>
            </tbody>
        </table>
       </div>


</section>

</div>


</body>
</html>