<?php
session_start();
if(!isset($_SESSION["username"])){
  header("Location: ../loginPage.php");
  exit;
}
include "../database.php";

$id = $_GET["id"] ?? "";
if($id==="") die("Missing id");

$stmt = $conn->prepare("SELECT appointmentnumber, username, pname, dname, session FROM appointment WHERE appointmentnumber=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$data) die("Appointment not found");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Appointment</title>

  <link rel="stylesheet" href="../css/doctor.css">
</head>
<body>
<div class="doctor">  
<div class="topbar">
  <h2>Appointment Details</h2>
  <a class="back_btn" href="../appointmentPage.php">Back</a>
</div>

<div class="form">
  <label>Appointment No</label><br>
  <input type="text" value="<?php echo htmlspecialchars($data["appointmentnumber"]); ?>" readonly> <br>

  <label>Username</label><br>
  <input type="text" value="<?php echo htmlspecialchars($data["username"]); ?>" readonly> <br>

  <label>Patient Name</label> <br>
  <input type="text" value="<?php echo htmlspecialchars($data["pname"]); ?>" readonly> <br>

  <label>Doctor Name</label> <br>
  <input type="text" value="<?php echo htmlspecialchars($data["dname"]); ?>" readonly> <br>

  <label>Session Time</label> <br>
  <input type="text" value="<?php echo htmlspecialchars($data["session"]); ?>" readonly> <br>
</div>
</div>
</body>
</html>
