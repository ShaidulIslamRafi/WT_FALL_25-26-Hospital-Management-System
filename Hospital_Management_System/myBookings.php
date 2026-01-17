<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: loginPage.php");
  exit();
}
$username = htmlspecialchars($_SESSION["username"]);

$base = rtrim(dirname(dirname($_SERVER["SCRIPT_NAME"])), "/");

$bookings = [
  ["session"=>"Heart Checkup", "doctor"=>"Dr. Sarah Ahmed", "dept"=>"Cardiology", "date"=>"2025-02-10", "time"=>"10:00 AM", "status"=>"Confirmed"],
  ["session"=>"Women Health", "doctor"=>"Dr. Nabila Sultana", "dept"=>"Gynecology", "date"=>"2025-02-12", "time"=>"09:00 AM", "status"=>"Pending"],
  ["session"=>"Diabetes Follow-up", "doctor"=>"Dr. Hasan Rahman", "dept"=>"Medicine", "date"=>"2025-02-11", "time"=>"02:00 PM", "status"=>"Cancelled"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Bookings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/myBookings.css">
</head>
<body>

<div class="app">

  <aside class="sidebar">
    <div class="logoTitle">Menu</div>

    <div class="profileBox">
      <div class="avatar">👤</div>
      <div class="profileText">
        <div class="name"><?php echo $username; ?></div>
        <div class="email">patient@edoc.com</div>
      </div>
    </div>

    <a class="logoutBtn" href="loginPage.php">Log out</a>

    <nav class="menu">
      <a class="menuItem" href="dashboard.php">Home</a>
      <a class="menuItem" href="doctorPage.php">All Doctors</a>
      <a class="menuItem" href="scheduledSessions.php">Scheduled Sessions</a>
      <a class="menuItem active" href="myBookings.php">My Bookings</a>
      <a class="menuItem" href="settings.php">Settings</a>

    </nav>
  </aside>

  <main class="main">

    <div class="topbar">
      <div>
        <h1 class="pageTitle">My Bookings</h1>
        <p class="subTitle">View, track, and manage your booked sessions.</p>
      </div>
    </div>

    <div class="tableCard">
      <table>
        <thead>
          <tr>
            <th>Session</th>
            <th>Doctor</th>
            <th>Department</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($bookings as $b): ?>
            <?php
              $statusClass = "statusGray";
              if ($b["status"] === "Confirmed") $statusClass = "statusGreen";
              if ($b["status"] === "Pending") $statusClass = "statusAmber";
              if ($b["status"] === "Cancelled") $statusClass = "statusRed";
            ?>
            <tr>
              <td><?php echo htmlspecialchars($b["session"]); ?></td>
              <td><?php echo htmlspecialchars($b["doctor"]); ?></td>
              <td><?php echo htmlspecialchars($b["dept"]); ?></td>
              <td><?php echo htmlspecialchars($b["date"]); ?></td>
              <td><?php echo htmlspecialchars($b["time"]); ?></td>
              <td>
                <span class="status <?php echo $statusClass; ?>">
                  <?php echo htmlspecialchars($b["status"]); ?>
                </span>
              </td>
              <td>
                <?php if ($b["status"] === "Pending"): ?>
                  <button class="dangerBtn">Cancel</button>
                <?php else: ?>
                  <span class="muted">—</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>

      </table>
    </div>

  </main>

</div>
</body>
</html>
