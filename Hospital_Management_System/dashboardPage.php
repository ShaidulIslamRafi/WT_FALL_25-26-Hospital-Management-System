<?php
   session_start();
   if(!isset($_SESSION["username"])){
    header("Location: loginPage.php");
    exit;
   }
   include "database.php";

$appSql = "SELECT appointmentnumber, username, pname, dname, session FROM appointment ORDER BY appointmentnumber DESC";
$appRes = $conn->query($appSql);

$sesSql = "SELECT sessiontitle, username, s_date_time FROM session ORDER BY sessiontitle DESC";
$sesRes = $conn->query($sesSql);
?>





<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <li><a href="updateProfile.php"><i class="fas fa-procedures"></i>Update Profile</a></li>
            <li class="logout"><a href="logout.php"><i class="fas fa-sign-out-alt"></i>LogOut</a></li>
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
                
                <div class="user_info">
                    
                    <span>
                        <?php echo $_SESSION["username"]; ?>
                    </span>
                    <i class="fas fa-user-circle"></i>
                </div>
                
            </div>
    </div>
    <h3 class="dashname"> Dashboard </h3>



    
               <!-- work on rightside middle-->
    <div class="value">
        <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3 id="doctorCount">0</h3>
                <span>Doctors</span>
            </div>
        </div>
         <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3 id="patientCount">0</h3>
                <span>Patients</span>
            </div>
        </div>
         <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3 id="bookingCount">0</h3>
                <span>Total Booking</span>
            </div>
        </div>
         <div class="box">
            <i class="fas fa-users"></i>
            <div>
                <h3 id="todayBookingCount"></h3>
                <span>Today Booking</span>
            </div>
        </div>
          
        <div class="dash-panel">  
            <div class="panel">  </div>

           <h2>All Appointments</h2>

<table border="1" cellpadding="8" cellspacing="4">
    <tr>
        <th>Appointment No</th>
        <th>Username</th>
        <th>Patient Name</th>
        <th>Doctor Name</th>
        <th>Session Time</th>
    </tr>

    <?php if ($appRes && $appRes->num_rows > 0) { ?>
        <?php while($row = $appRes->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["appointmentnumber"]); ?></td>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo htmlspecialchars($row["pname"]); ?></td>
                <td><?php echo htmlspecialchars($row["dname"]); ?></td>
                <td><?php echo htmlspecialchars($row["session"]); ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr><td colspan="5">No appointments found</td></tr>
    <?php } ?>
</table>


</div>

<div class="panel">
<h2>All Sessions</h2>

<table border="1" cellpadding="8" cellspacing="4">
    <tr>
        <th>Session Title (ID)</th>
        <th>Username</th>
        <th>Scheduled Date & Time</th>
    </tr>

    <?php if ($sesRes && $sesRes->num_rows > 0) { ?>
        <?php while($row = $sesRes->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["sessiontitle"]); ?></td>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo htmlspecialchars($row["s_date_time"]); ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr><td colspan="3">No sessions found</td></tr>
    <?php } ?>


</table>

        
</div>
                 



    
             </div>
        </div>
           
         



    </div>

</section>

</div>


</body>

<script src="javaScript/ajax.js"></script>



</html>