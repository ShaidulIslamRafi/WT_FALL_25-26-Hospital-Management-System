<?php
session_start();
if(!isset($_SESSION["username"])){
  header("Location:../loginPage.php");
  exit;
}
include "../database.php";

$error = "";
$docRes = $conn->query("SELECT username, dname FROM doctor ORDER BY dname ASC");

if(isset($_POST["save"])){

  $username = trim($_POST["username"]);   
  $pname    = trim($_POST["pname"]);      
  $dname    = trim($_POST["dname"]);      
  $session  = trim($_POST["session"]);    

  if($username=="" || $pname=="" || $dname=="" || $session==""){
    $error = "All fields are required!";
  } else {

    
    $session = str_replace("T", " ", $session) . ":00";

    $stmt = $conn->prepare("INSERT INTO appointment (username, pname, dname, session) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $username, $pname, $dname, $session);

    if($stmt->execute()){
      header("Location:../appointmentPage.php");
      exit;
    } else {
      $error = "Insert failed! " . $stmt->error;
    }
    $stmt->close();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Appointment</title>
  <link rel="stylesheet" href="../css/doctor.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<div class="doctor"> 
<div class="topbar">
  <h2>Add New Appointment</h2>
  <a class="back_btn" href="../appointmentPage.php">Back</a>
</div>

<?php if($error!="") echo "<p class='error_msg'>".htmlspecialchars($error)."</p>"; ?>

<form method="post" class="form">

  <label>Patient Username (Email)</label><br>
  <input type="text" name="username" required> <br>

  <label>Patient Name</label> <br>
  <input type="text" name="pname" required> <br>

  <label>Select Doctor</label><br>
<select name="dname" required>
  <option value="">Choose Doctor</option>
  <?php while($d = $docRes->fetch_assoc()){ ?>
    <option value="<?php echo htmlspecialchars($d["username"]); ?>">
      <?php echo htmlspecialchars($d["dname"]) . " (" . htmlspecialchars($d["username"]) . ")"; ?>
    </option>
  <?php } ?>
</select><br>

  <label>Session Time</label><br>
  <input type="datetime-local" name="session" required><br>

  <button type="submit" name="save" class="btn">Save</button><br>

</form>
</div>
</body>
</html>
