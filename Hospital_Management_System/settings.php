<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: loginPage.php");
  exit();
}
$username = htmlspecialchars($_SESSION["username"]);

$base = rtrim(dirname(dirname($_SERVER["SCRIPT_NAME"])), "/");

$userEmail = "patient@edoc.com";
$userPhone = "01XXXXXXXXX";
$userAddress = "Dhaka, Bangladesh";
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
    <div class="logoTitle">Menu</div>

    <div class="profileBox">
      <div class="avatar">👤</div>
      <div class="profileText">
        <div class="name"><?php echo $username; ?></div>
        <div class="email"><?php echo htmlspecialchars($userEmail); ?></div>
      </div>
    </div>

    <a class="logoutBtn" href="loginPage.php">Log out</a>

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
      <div>
        <h1 class="pageTitle">Settings</h1>
        <p class="subTitle">Manage your profile details and account security.</p>
      </div>
    </div>

    <div class="settingsGrid">

      <section class="card">
        <div class="cardTitle">Profile Information</div>
        <p class="cardSub">Update your personal details (demo only for now).</p>

        <form class="form" action="#" method="post">
          <div class="row">
            <div class="field">
              <label>Full Name</label>
              <input type="text" value="<?php echo $username; ?>" />
            </div>
            <div class="field">
              <label>Email</label>
              <input type="email" value="<?php echo htmlspecialchars($userEmail); ?>" />
            </div>
          </div>

          <div class="row">
            <div class="field">
              <label>Phone</label>
              <input type="text" value="<?php echo htmlspecialchars($userPhone); ?>" />
            </div>
            <div class="field">
              <label>Address</label>
              <input type="text" value="<?php echo htmlspecialchars($userAddress); ?>" />
            </div>
          </div>

          <button class="primaryBtn" type="button">Save Changes</button>
          <span class="note">*Demo UI (connect DB later)</span>
        </form>
      </section>

      <section class="card">
        <div class="cardTitle">Security</div>
        <p class="cardSub">Change your password (UI only).</p>

        <form class="form" action="#" method="post">
          <div class="field">
            <label>Current Password</label>
            <input type="password" placeholder="••••••••" />
          </div>

          <div class="field">
            <label>New Password</label>
            <input type="password" placeholder="••••••••" />
          </div>

          <div class="field">
            <label>Confirm New Password</label>
            <input type="password" placeholder="••••••••" />
          </div>

          <button class="dangerBtn" type="button">Update Password</button>
          <span class="note">*Demo UI (connect DB later)</span>
        </form>
      </section>

    </div>

  </main>

</div>

</body>
</html>
