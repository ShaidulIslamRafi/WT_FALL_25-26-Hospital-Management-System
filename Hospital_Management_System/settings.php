<?php
session_start();
if (!isset($_SESSION["email"])) {
  header("Location: loginPage.php");
  exit();
}

require_once "php/db.php";

$email = $_SESSION["email"];
$msg = "";
$err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
  $fname  = trim($_POST["fname"]);
  $lname  = trim($_POST["lname"]);
  $age    = intval($_POST["age"]);
  $bgroup = trim($_POST["bgroup"]);

  $stmt = mysqli_prepare($conn,
    "UPDATE users SET fname=?, lname=?, age=?, bgroup=? WHERE email=?"
  );
  mysqli_stmt_bind_param($stmt, "ssiss", $fname, $lname, $age, $bgroup, $email);

  if (mysqli_stmt_execute($stmt)) {
    $msg = "Profile updated successfully.";
  } else {
    $err = "Failed to update profile.";
  }
}

/* Handle Password Update */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_password"])) {
  $current = $_POST["current_password"];
  $new1    = $_POST["new_password"];
  $new2    = $_POST["confirm_password"];

  if ($new1 !== $new2) {
    $err = "Passwords do not match.";
  } elseif (strlen($new1) < 6) {
    $err = "Password must be at least 6 characters.";
  } else {
    $q = mysqli_prepare($conn, "SELECT password FROM users WHERE email=?");
    mysqli_stmt_bind_param($q, "s", $email);
    mysqli_stmt_execute($q);
    $res = mysqli_stmt_get_result($q);
    $row = mysqli_fetch_assoc($res);

    if ($row && $row["password"] !== $current) {
      $err = "Current password is incorrect.";
    } else {
      $up = mysqli_prepare($conn,
        "UPDATE users SET password=? WHERE email=?"
      );
      mysqli_stmt_bind_param($up, "ss", $new1, $email);

      if (mysqli_stmt_execute($up)) {
        $msg = "Password updated successfully.";
      } else {
        $err = "Password update failed.";
      }
    }
  }
}


$stmt = mysqli_prepare($conn,
  "SELECT fname,lname,age,bgroup,email,role FROM users WHERE email=? LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$fullname = $user["fname"] . " " . $user["lname"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/settings.css">
</head>
<body>

<div class="app">

  <aside class="sidebar">


    <div class="profileBox">
      <div class="avatar">👤</div>
      <div class="profileText">
        <div class="name"><?php echo htmlspecialchars($fullname); ?></div>
        <div class="email"><?php echo htmlspecialchars($email); ?></div>
      </div>
    </div>

    <a class="logoutBtn" href="logout.php">Log out</a>

    <nav class="menu">
      <a class="menuItem" href="dashboard.php">Home</a>
      <a class="menuItem" href="doctorPage.php">All Doctors</a>
      <a class="menuItem" href="scheduledSessions.php">Scheduled Sessions</a>
      <a class="menuItem" href="myBookings.php">My Bookings</a>
      <a class="menuItem active" href="settings.php">Settings</a>
    </nav>
  </aside>

  <main class="main">

    <div class="topbar">
      <h1 class="pageTitle">Settings</h1>
      <p class="subTitle">Manage your profile & security</p>

      <?php if ($msg): ?><p style="color:#16a34a;font-weight:900"><?php echo $msg; ?></p><?php endif; ?>
      <?php if ($err): ?><p style="color:#dc2626;font-weight:900"><?php echo $err; ?></p><?php endif; ?>
    </div>

    <div class="settingsGrid">

      <section class="card">
        <div class="cardTitle">Profile Information</div>

        <form class="form" method="post">
          <input type="hidden" name="update_profile" value="1">

          <div class="row">
            <div class="field">
              <label>First Name</label>
              <input name="fname" value="<?php echo htmlspecialchars($user["fname"]); ?>">
            </div>
            <div class="field">
              <label>Last Name</label>
              <input name="lname" value="<?php echo htmlspecialchars($user["lname"]); ?>">
            </div>
          </div>

          <div class="row">
            <div class="field">
              <label>Age</label>
              <input name="age" value="<?php echo htmlspecialchars($user["age"]); ?>">
            </div>
            <div class="field">
              <label>Blood Group</label>
              <input name="bgroup" value="<?php echo htmlspecialchars($user["bgroup"]); ?>">
            </div>
          </div>

          <button class="primaryBtn">Save Changes</button>
        </form>
      </section>

      <section class="card">
        <div class="cardTitle">Security</div>

        <form class="form" method="post">
          <input type="hidden" name="update_password" value="1">

          <div class="field">
            <label>Current Password</label>
            <input type="password" name="current_password">
          </div>

          <div class="field">
            <label>New Password</label>
            <input type="password" name="new_password">
          </div>

          <div class="field">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password">
          </div>

          <button class="dangerBtn">Update Password</button>
        </form>
      </section>

    </div>

  </main>

</div>
</body>
</html>
