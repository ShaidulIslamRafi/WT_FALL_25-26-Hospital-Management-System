<?php
session_start();
if(!isset($_SESSION["username"])){
  header("Location: ../loginPage.php");
  exit;
}
include "../database.php";

$id = $_GET["id"] ?? "";
if($id=="") die("Missing id");

$stmt = $conn->prepare("
  SELECT s.sessiontitle, s.username, s.s_date_time, d.dname, d.specialist, d.phone
  FROM session s
  LEFT JOIN doctor d ON d.username=s.username
  WHERE s.sessiontitle=?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$row) die("Session not found");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Session</title>
  <link rel="stylesheet" href="../css/doctor.css">
</head>
<body>
        <div class="doctor">       
<div class="topbar">
  <h2>Session Details</h2>
  <a class="back_btn" href="../schedulePage.php">Back</a>
</div>

<div class="view_box">
  <p><b>Session ID:</b> <?php echo htmlspecialchars($row["sessiontitle"]); ?></p>
  <p><b>Doctor Username:</b> <?php echo htmlspecialchars($row["username"]); ?></p>
  <p><b>Doctor Name:</b> <?php echo htmlspecialchars($row["dname"] ?? ""); ?></p>
  <p><b>Specialist:</b> <?php echo htmlspecialchars($row["specialist"] ?? ""); ?></p>
  <p><b>Phone:</b> <?php echo htmlspecialchars($row["phone"] ?? ""); ?></p>
  <p><b>Date & Time:</b> <?php echo htmlspecialchars($row["s_date_time"]); ?></p>
</div>
</div>   
</body>
</html>
