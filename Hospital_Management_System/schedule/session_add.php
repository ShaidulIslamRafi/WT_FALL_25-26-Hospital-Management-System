<?php
session_start();
if(!isset($_SESSION["username"])){
  header("Location:../loginPage.php");
  exit;
}


if(isset($_SESSION["roles"]) && $_SESSION["roles"] !== "admin"){
  header("Location: ../loginPage.php");
  exit;
}

include "../database.php";

$error = "";


$docRes = $conn->query("SELECT username, dname FROM doctor ORDER BY dname ASC");

if(isset($_POST["save"])){
  $doc_username = trim($_POST["username"]);
  $s_date_time  = trim($_POST["s_date_time"]);

  if($doc_username=="" || $s_date_time==""){
    $error = "All fields are required!";
  } else {
    $stmt = $conn->prepare("INSERT INTO session (username, s_date_time) VALUES (?, ?)");
    $stmt->bind_param("ss", $doc_username, $s_date_time);

    if($stmt->execute()){
      header("Location: ../schedulePage.php");
      exit;
    } else {
      $error = "Insert Failed! " . $stmt->error;
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
  <title>Add Session</title>
  <link rel="stylesheet" href="../css/schedule.css">
</head>
<body>

<div class="topbar">
  <h2>Add New Session</h2>
  <a class="back_btn" href="../schedulePage.php">Back</a>
</div>

<?php if($error!="") echo "<p class='error_msg'>$error</p>"; ?>

<form method="post" class="form">

  <label>Doctor</label>
  <select name="username" required>
    <option value="">Select Doctor</option>
    <?php if($docRes && $docRes->num_rows>0){ ?>
      <?php while($d = $docRes->fetch_assoc()){ ?>
        <option value="<?php echo htmlspecialchars($d["username"]); ?>">
          <?php echo htmlspecialchars($d["dname"]) . " (" . htmlspecialchars($d["username"]) . ")"; ?>
        </option>
      <?php } ?>
    <?php } ?>
  </select>

  <label>Scheduled Date & Time</label>
  <input type="datetime-local" name="s_date_time" required>

  <button type="submit" name="save" class="add_btn">Save</button>

</form>

</body>
</html>
