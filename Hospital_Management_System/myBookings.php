<?php
session_start();
require_once "php/db.php";

if (!isset($_SESSION["email"]) || !isset($_SESSION["user_id"])) {
  header("Location: loginPage.php");
  exit();
}

if (isset($_SESSION["role"]) && $_SESSION["role"] !== "patient") {
  header("Location: adminPage.php");
  exit();
}

$userEmail = $_SESSION["email"];
$userId = (int)$_SESSION["user_id"];

$stmtU = mysqli_prepare($conn, "SELECT fname, lname, email FROM users WHERE id=? LIMIT 1");
mysqli_stmt_bind_param($stmtU, "i", $userId);
mysqli_stmt_execute($stmtU);
$resU = mysqli_stmt_get_result($stmtU);
$user = mysqli_fetch_assoc($resU);
mysqli_stmt_close($stmtU);

if (!$user) {
  session_unset();
  session_destroy();
  header("Location: loginPage.php");
  exit();
}

$fullName = trim(($user["fname"] ?? "") . " " . ($user["lname"] ?? ""));
if ($fullName === "") $fullName = "Patient";
$safeEmail = htmlspecialchars($user["email"] ?? "");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_booking_id"])) {
  $bid = (int)$_POST["delete_booking_id"];

  $del = mysqli_prepare($conn, "DELETE FROM bookings WHERE id = ? AND username = ?");
  mysqli_stmt_bind_param($del, "is", $bid, $userEmail);
  mysqli_stmt_execute($del);
  mysqli_stmt_close($del);

  header("Location: myBookings.php?deleted=1");
  exit();
}

$bookings = [];
$sql = "
  SELECT b.id, b.session_title, b.dept, b.book_date, b.book_time, b.status,
         d.name AS doctor_name
  FROM bookings b
  LEFT JOIN doctors d ON b.doctor_id = d.id
  WHERE b.username = ?
  ORDER BY b.book_date DESC, b.created_at DESC
";
$st = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($st, "s", $userEmail);
mysqli_stmt_execute($st);
$rs = mysqli_stmt_get_result($st);

while ($row = mysqli_fetch_assoc($rs)) $bookings[] = $row;
mysqli_stmt_close($st);
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
        <div class="name"><?php echo htmlspecialchars($fullName); ?></div>
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
        <p class="subTitle">Here you can delete any booking.</p>

        <?php if (isset($_GET["deleted"])): ?>
          <p style="margin-top:10px;font-weight:800;color:#ef4444;"> Booking deleted.</p>
        <?php endif; ?>
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
          <?php if (count($bookings) === 0): ?>
            <tr><td colspan="7">No bookings found.</td></tr>
          <?php else: ?>
            <?php foreach ($bookings as $b): ?>
              <?php
                $status = $b["status"] ?? "Pending";
                $statusClass = "statusGray";
                if ($status === "Confirmed") $statusClass = "statusGreen";
                if ($status === "Pending") $statusClass = "statusAmber";
                if ($status === "Cancelled") $statusClass = "statusRed";
              ?>
              <tr>
                <td><?php echo htmlspecialchars($b["session_title"]); ?></td>
                <td><?php echo htmlspecialchars($b["doctor_name"] ?? "Unknown"); ?></td>
                <td><?php echo htmlspecialchars($b["dept"]); ?></td>
                <td><?php echo htmlspecialchars($b["book_date"]); ?></td>
                <td><?php echo htmlspecialchars($b["book_time"]); ?></td>
                <td><span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                <td>
                  <form method="POST" action="" onsubmit="return confirm('Delete this booking?');" style="margin:0;">
                    <input type="hidden" name="delete_booking_id" value="<?php echo (int)$b["id"]; ?>">
                    <button class="dangerBtn" type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>

      </table>
    </div>
  </main>

</div>
</body>
</html>
