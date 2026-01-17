<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: loginPage.php");
  exit();
}
$username = htmlspecialchars($_SESSION["username"]);

// ✅ Auto-detect project base (ex: /HOSPITAL_MANAGEMENT_SYSTEM)
$base = rtrim(dirname(dirname($_SERVER["SCRIPT_NAME"])), "/");

/* Demo sessions (replace with DB later) */
$sessions = [
  ["title"=>"Heart Checkup", "doctor"=>"Dr. Sarah Ahmed", "dept"=>"Cardiology", "date"=>"2025-02-10", "time"=>"10:00 AM", "status"=>"Available"],
  ["title"=>"Diabetes Follow-up", "doctor"=>"Dr. Hasan Rahman", "dept"=>"Medicine", "date"=>"2025-02-11", "time"=>"02:00 PM", "status"=>"Booked"],
  ["title"=>"Women Health", "doctor"=>"Dr. Nabila Sultana", "dept"=>"Gynecology", "date"=>"2025-02-12", "time"=>"09:00 AM", "status"=>"Available"],
  ["title"=>"Bone Consultation", "doctor"=>"Dr. Imran Hossain", "dept"=>"Orthopedics", "date"=>"2025-02-13", "time"=>"04:00 PM", "status"=>"Closed"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Scheduled Sessions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- ✅ Guaranteed correct CSS path -->
  <link rel="stylesheet" href="css/scheduleSessions.css">

  <!-- Optional icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app">

  <aside class="sidebar">
    <div class="logoTitle">Menu</div>

    <div class="profileBox">
      <div class="avatar"><i class="fa-regular fa-user"></i></div>
      <div class="profileText">
        <div class="name"><?php echo $username; ?></div>
        <div class="email">patient@edoc.com</div>
      </div>
    </div>

    <a class="logoutBtn" href="loginPage.php">Log out</a>

    <nav class="menu">
      <a class="menuItem" href="dashboard.php">Home</a>
      <a class="menuItem" href="doctorPage.php">All Doctors</a>
      <a class="menuItem active" href="scheduledSessions.php">Scheduled Sessions</a>
    <a class="menuItem" href="myBookings.php">
  <span>My Bookings</span>
</a>

      <a class="menuItem" href="settings.php">Settings</a>

    </nav>
  </aside>

  <main class="main">
    <div class="topbar">
      <div>
        <h1 class="pageTitle">Scheduled Sessions</h1>
        <p class="subTitle">View and manage upcoming doctor sessions.</p>
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
          <?php foreach ($sessions as $s): ?>
            <?php
              $statusClass = "statusGray";
              if ($s["status"] === "Available") $statusClass = "statusGreen";
              if ($s["status"] === "Booked") $statusClass = "statusAmber";
              if ($s["status"] === "Closed") $statusClass = "statusRed";
            ?>
            <tr>
              <td><?php echo htmlspecialchars($s["title"]); ?></td>
              <td><?php echo htmlspecialchars($s["doctor"]); ?></td>
              <td><?php echo htmlspecialchars($s["dept"]); ?></td>
              <td><?php echo htmlspecialchars($s["date"]); ?></td>
              <td><?php echo htmlspecialchars($s["time"]); ?></td>
              <td><span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($s["status"]); ?></span></td>
              <td>
                <?php if ($s["status"] === "Available"): ?>
                  <button class="bookBtn" type="button">Book</button>
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
