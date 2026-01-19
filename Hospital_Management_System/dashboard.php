<?php
session_start();
require_once "php/db.php";

if (!isset($_SESSION["email"])) {
  header("Location: loginPage.php");
  exit();
}

if (isset($_SESSION["role"]) && $_SESSION["role"] !== "patient") {
  header("Location: adminPage.php");
  exit();
}

$userId = (int)$_SESSION["user_id"];

$stmt = mysqli_prepare($conn, "SELECT fname, lname, email FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$user) {
  session_unset();
  session_destroy();
  header("Location: loginPage.php");
  exit();
}

$fullName = trim(($user["fname"] ?? "") . " " . ($user["lname"] ?? ""));
if ($fullName === "") $fullName = "Patient";
$userEmail = $user["email"] ?? "";

$todaySessions = [];
$todaySql = "
  SELECT 
    b.id,
    d.name AS doctor_name,
    b.session_title,
    b.dept,
    b.book_time,
    b.status
  FROM bookings b
  LEFT JOIN doctors d ON b.doctor_id = d.id
  WHERE b.book_date = CURDATE()
    AND b.username = ? 
  ORDER BY b.book_time ASC
";

$stmtToday = mysqli_prepare($conn, $todaySql);
mysqli_stmt_bind_param($stmtToday, "s", $userEmail);
mysqli_stmt_execute($stmtToday);
$todayRes = mysqli_stmt_get_result($stmtToday);

if ($todayRes) {
  while ($row = mysqli_fetch_assoc($todayRes)) {
    $todaySessions[] = $row;
  }
}
mysqli_stmt_close($stmtToday);

$upcomingBookings = [];
$bookingSql = "
  SELECT 
    b.id,
    d.name AS doctor_name,
    b.session_title,
    b.dept,
    b.book_date,
    b.book_time,
    b.status
  FROM bookings b
  LEFT JOIN doctors d ON b.doctor_id = d.id
  WHERE b.username = ?
    AND b.book_date >= CURDATE()
  ORDER BY b.book_date ASC, b.book_time ASC
  LIMIT 5
";

$stmt2 = mysqli_prepare($conn, $bookingSql);
if (!$stmt2) {
  die("Upcoming booking query failed: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt2, "s", $userEmail);
mysqli_stmt_execute($stmt2);
$res2 = mysqli_stmt_get_result($stmt2);

while ($row = mysqli_fetch_assoc($res2)) {
  $upcomingBookings[] = $row;
}
mysqli_stmt_close($stmt2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app">
  <aside class="sidebar">
    <div class="profileBox">
      <div class="avatar"><i class="fa-regular fa-user"></i></div>
      <div class="profileText">
        <div class="name"><strong><?php echo htmlspecialchars($fullName); ?></strong></div>
        <div class="email"><?php echo htmlspecialchars($userEmail); ?></div>
      </div>
    </div>

    <a class="logoutBtn" href="logout.php">Log out</a>

    <nav class="menu">
      <a class="menuItem active" href="dashboard.php">
        <i class="fa-solid fa-house"></i><span>Home</span>
      </a>
      <a class="menuItem" href="doctorPage.php">
        <i class="fa-regular fa-square-plus"></i><span>All Doctors</span>
      </a>
      <a class="menuItem" href="scheduledSessions.php">
        <i class="fa-regular fa-calendar"></i><span>Scheduled Sessions</span>
      </a>
      <a class="menuItem" href="myBookings.php">
        <i class="fa-regular fa-bookmark"></i><span>My Bookings</span>
      </a>
      <a class="menuItem" href="settings.php">
        <i class="fa-solid fa-gear"></i><span>Settings</span>
      </a>
    </nav>
  </aside>

  <main class="main">
    <div class="topbar">
      <h1 class="pageTitle">Home</h1>
      <div class="dateBox">
        <div class="dateText">
          <div class="small">Today's Date</div>
          <div class="big" id="todayDate">0000-00-00</div>
        </div>
        <button class="calBtn" type="button" title="Calendar">
          <i class="fa-regular fa-calendar"></i>
        </button>
      </div>
    </div>

    <section class="hero">
      <div class="heroText">
        <div class="welcome">Welcome!</div>
        <div class="heroName"><?php echo htmlspecialchars($fullName); ?>.</div>
        <p class="heroDesc">View today’s sessions and your upcoming bookings.</p>
        <div class="heroSearchTitle">Quick Search</div>
        <div class="searchRow">
          <div class="searchInput">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search doctor/session...">
          </div>
          <button class="searchBtn" type="button">Search</button>
        </div>
      </div>
      <div class="heroImage" aria-hidden="true"></div>
    </section>

    <section class="lower">
      <div class="leftCol">
        <h2 class="sectionTitle">Today Sessions</h2>
        <div class="tableCard">
          <table>
            <thead>
              <tr>
                <th>Session</th>
                <th>Doctor</th>
                <th>Dept</th>
                <th>Time</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($todaySessions) === 0): ?>
                <tr><td colspan="5" style="text-align:center; padding:20px;">No sessions for today.</td></tr>
              <?php else: ?>
                <?php foreach ($todaySessions as $s): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($s["session_title"]); ?></td>
                    <td><?php echo htmlspecialchars($s["doctor_name"] ?? "Unknown"); ?></td>
                    <td><?php echo htmlspecialchars($s["dept"]); ?></td>
                    <td><?php echo htmlspecialchars($s["book_time"]); ?></td>
                    <td><?php echo htmlspecialchars($s["status"]); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="rightCol">
        <h2 class="sectionTitle">Your Upcoming Booking</h2>
        <div class="tableCard">
          <table>
            <thead>
              <tr>
                <th>Session</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($upcomingBookings) === 0): ?>
                <tr><td colspan="5" style="text-align:center; padding:20px;">No upcoming bookings.</td></tr>
              <?php else: ?>
                <?php foreach ($upcomingBookings as $b): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($b["session_title"]); ?></td>
                    <td><?php echo htmlspecialchars($b["doctor_name"] ?? "Unknown"); ?></td>
                    <td><?php echo htmlspecialchars($b["book_date"]); ?></td>
                    <td><?php echo htmlspecialchars($b["book_time"]); ?></td>
                    <td><?php echo htmlspecialchars($b["status"]); ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
</div>

<script>
  const d = new Date();
  const pad = (n) => String(n).padStart(2, "0");
  document.getElementById("todayDate").textContent =
    d.getFullYear() + "-" + pad(d.getMonth()+1) + "-" + pad(d.getDate());
</script>
</body>
</html>