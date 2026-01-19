<?php
session_start();
require_once "php/db.php";

if (!isset($_SESSION["email"])) {
  header("Location: loginPage.php");
  exit();
}

$email = $_SESSION["email"];

// ✅ Fetch user full name
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

/* Demo bookings (replace with DB later) */
$bookings = [
  ["id"=>1, "session"=>"Heart Checkup", "doctor"=>"Dr. Sarah Ahmed", "dept"=>"Cardiology", "date"=>"2026-03-01", "time"=>"10:00 AM - 12:00 PM", "status"=>"Pending"],
  ["id"=>2, "session"=>"General Medicine OPD", "doctor"=>"Dr. Hasan Rahman", "dept"=>"Medicine", "date"=>"2026-03-02", "time"=>"02:00 PM - 04:00 PM", "status"=>"Confirmed"],
  ["id"=>3, "session"=>"Psychiatry Appointment", "doctor"=>"Dr. Ayesha Rahman", "dept"=>"Psychiatry", "date"=>"2026-03-05", "time"=>"10:00 AM - 12:00 PM", "status"=>"Cancelled"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Bookings</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="css/myBookings.css">
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

    <a class="logoutBtn" href="logout.php">Log out</a>

    <nav class="menu">
      <a class="menuItem" href="dashboard.php"><i class="fa-solid fa-house"></i><span>Home</span></a>
      <a class="menuItem" href="doctorPage.php"><i class="fa-solid fa-user-doctor"></i><span>All Doctors</span></a>
      <a class="menuItem" href="scheduledSessions.php"><i class="fa-regular fa-calendar"></i><span>Scheduled Sessions</span></a>
      <a class="menuItem active" href="myBookings.php"><i class="fa-regular fa-bookmark"></i><span>My Bookings</span></a>
      <a class="menuItem" href="settings.php"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
    </nav>

  </aside>

  <main class="main">

    <div class="topbar">
      <div>
        <h1 class="pageTitle">My Bookings</h1>
        <p class="subTitle">View, track, and delete your bookings.</p>
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
              <td><span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($b["status"]); ?></span></td>
              <td>
                <!-- ✅ Delete button -->
                <button class="dangerBtn" type="button">Delete</button>
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
