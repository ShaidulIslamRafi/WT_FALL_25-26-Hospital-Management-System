<?php
   session_start();
   if(!isset($_SESSION["username"])){
    header("Location: loginPage.php");
    exit;
   }
   include "database.php";
    $result= $conn->query("SELECT username,dname,phone,specialist FROM doctor ORDER BY created_at DESC");
    $total=$result->num_rows;




?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> </title>
<link  rel="stylesheet" href="css/doctor.css">
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



<div class="topbar">
    <h2>Add new doctor </h2>
    <a class="add_btn"href="doctor/doctor_add.php"> <i class="fas fa-plus"> </i>Add new</a>
</div>
  <h3 class="sub">All doctors(<?php echo $total;?>) </h3>
  <div class="table">
    <table>
        <thead>
            <tr>
                <th>Doctor Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Specialist</th>
                <th>Events</th>
                
            </tr>
        </thead>
        <tbody>
            <?php while($row= $result->fetch_assoc()):?>
            <tr>
                <td><?php echo htmlspecialchars($row["dname"]);?> </td>
                <td><?php echo htmlspecialchars($row["username"]);?>  </td>
                 <td><?php echo htmlspecialchars($row["phone"]);?> </td>
                <td><?php echo htmlspecialchars($row["specialist"]);?> </td>
                <td class="actions">
                 <a class="edit_btn"href="doctor/doctor_edit.php?username=<?php echo urlencode($row["username"]);?>">
                    <i class="fas fa-pen"></i>Edit
                 </a>    
                  <a class="view_btn"href="doctor/doctor_view.php?username=<?php echo urlencode($row["username"]);?>">
                    <i class="fas fa-eye"></i>View
                 </a>  
                  <a class="remove_btn"href="doctor/doctor_delete.php?username=<?php echo urlencode($row["username"]);?>"
                    onclick="return confirm('Remove this doctor?');">
                    <i class="fas fa-trash"></i>Remove
                 </a>  

                </td>
            
            </tr>
            <?php endwhile;?>
        </tbody>
    </table>

  </div>



</section>

</div>

</body>
</html>