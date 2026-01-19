
<?php
   session_start();
   if(!isset($_SESSION["username"])){
    header("Location: loginPage.php");
    exit;
   }

include "database.php";

$sql = "SELECT appointmentnumber, username, pname, dname, session FROM appointment ORDER BY appointmentnumber DESC";
$result = $conn->query($sql);
$total = $result ? $result->num_rows : 0;





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
            <li><a href="updateProfile.php"><i class="fas fa-user-edit"></i>Update Profile</a></li>
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
                       <!-- work on rightside lower-->
     <div class="topbar">
      <h2>Appointment Manager</h2>
      <a class="add_btn" href="appointment/appointment_add.php">
        <i class="fas fa-plus"></i> Add New
      </a>
    </div>

    <h3 class="sub">All Appointments(<?php echo $total; ?>)</h3>

    <div class="table">
      <table>
        <thead>
          <tr>
            <th>Appointment No</th>
            <th>Username</th>
            <th>Patient Name</th>
            <th>Doctor Name</th>
            <th>Session Time</th>
            <th>Events</th>
          </tr>
        </thead>

        <tbody>
        <?php if($result && $result->num_rows > 0){ ?>
          <?php while($row = $result->fetch_assoc()){ ?>
            <tr>
              <td><?php echo htmlspecialchars($row["appointmentnumber"]); ?></td>
              <td><?php echo htmlspecialchars($row["username"]); ?></td>
              <td><?php echo htmlspecialchars($row["pname"]); ?></td>
              <td><?php echo htmlspecialchars($row["dname"]); ?></td>
              <td><?php echo htmlspecialchars($row["session"]); ?></td>

              <td class="actions">
                <a class="view_btn"
                   href="appointment/appointment_view.php?id=<?php echo urlencode($row["appointmentnumber"]); ?>">
                  <i class="fas fa-eye"></i> View
                </a>

                <a class="remove_btn"
                   href="appointment/appointment_delete.php?id=<?php echo urlencode($row["appointmentnumber"]); ?>"
                   onclick="return confirm('Delete this appointment?');">
                  <i class="fas fa-trash"></i> Remove</a>
              </td>
            </tr>
          <?php
          } ?>
        <?php } 
        else { 
            ?>
          <tr>
            <td colspan="6" style="text-align:center;">No appointments found</td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>   



</section>

</div>


</body>
</html>