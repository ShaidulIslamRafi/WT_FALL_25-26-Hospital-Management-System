<?php
session_start();
require_once "php/db.php";

if (!isset($_SESSION["email"])) {
  header("Location: loginPage.php");
  exit();
}

$email = $_SESSION["email"];


$ustmt = mysqli_prepare($conn, "SELECT fname, lname, email FROM users WHERE email=? LIMIT 1");
mysqli_stmt_bind_param($ustmt, "s", $email);
mysqli_stmt_execute($ustmt);
$ures = mysqli_stmt_get_result($ustmt);
$user = mysqli_fetch_assoc($ures);
mysqli_stmt_close($ustmt);

if (!$user) {
  session_unset();
  session_destroy();
  header("Location: loginPage.php");
  exit();
}

$fullName = trim(($user["fname"] ?? "")." ".($user["lname"] ?? ""));
if ($fullName === "") $fullName = "Patient";

$safeName = htmlspecialchars($fullName);
$safeEmail = htmlspecialchars($user["email"] ?? $email);


$sessions = [
  ["title"=>"Heart Checkup", "doctor"=>"Dr. Sarah Ahmed", "dept"=>"Cardiology", "date"=>"2026-03-01", "time"=>"10:00 AM - 12:00 PM", "status"=>"Available"],
  ["title"=>"General Medicine OPD", "doctor"=>"Dr. Hasan Rahman", "dept"=>"Medicine", "date"=>"2026-03-02", "time"=>"02:00 PM - 04:00 PM", "status"=>"Available"],
  ["title"=>"ENT Consultation", "doctor"=>"Dr. Rafiul Islam", "dept"=>"ENT", "date"=>"2026-03-03", "time"=>"11:00 AM - 01:00 PM", "status"=>"Booked"],
  ["title"=>"Neurology Follow-up", "doctor"=>"Dr. Tousif Khan", "dept"=>"Neurology", "date"=>"2026-03-04", "time"=>"09:00 AM - 11:00 AM", "status"=>"Available"],
  ["title"=>"Psychiatry Session", "doctor"=>"Dr. Ayesha Rahman", "dept"=>"Psychiatry", "date"=>"2026-03-05", "time"=>"10:00 AM - 12:00 PM", "status"=>"Closed"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Scheduled Sessions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  
  <link rel="stylesheet" href="css/scheduleSessions.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app">


  <aside class="sidebar">

    <div class="profileBox">
      <div class="avatar"><i class="fa-regular fa-user"></i></div>
      <div class="profileText">
        <div class="name"><?php echo $safeName; ?></div>
        <div class="email"><?php echo $safeEmail; ?></div>
      </div>
    </div>

    <a class="logoutBtn" href="loginPage.php">Log out</a>

    <nav class="menu">
      <a class="menuItem" href="dashboard.php"><i class="fa-solid fa-house"></i><span>Home</span></a>
      <a class="menuItem" href="doctorPage.php"><i class="fa-solid fa-user-doctor"></i><span>All Doctors</span></a>
      <a class="menuItem active" href="scheduledSessions.php"><i class="fa-regular fa-calendar"></i><span>Scheduled Sessions</span></a>
      <a class="menuItem" href="myBookings.php"><i class="fa-regular fa-bookmark"></i><span>My Bookings</span></a>
      <a class="menuItem" href="settings.php"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
    </nav>

  </aside>

  <main class="main">

    <div class="topbar">
      <div>
        <h1 class="pageTitle">Scheduled Sessions</h1>
        <p class="subTitle">Browse available sessions and book a doctor appointment.</p>
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
