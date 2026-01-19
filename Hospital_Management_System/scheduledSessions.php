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


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["book_session_id"])) {
  $sessionId = (int)$_POST["book_session_id"];

  $sql = "
    SELECT s.id, s.title, s.dept, s.session_date, s.session_time,
           s.doctor_id, d.name AS doctor_name
    FROM sessions s
    LEFT JOIN doctors d ON s.doctor_id = d.id
    WHERE s.id = ?
    LIMIT 1
  ";
  $st = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($st, "i", $sessionId);
  mysqli_stmt_execute($st);
  $rs = mysqli_stmt_get_result($st);
  $sess = mysqli_fetch_assoc($rs);
  mysqli_stmt_close($st);

  if ($sess) {
  
    $ins = "
      INSERT INTO bookings (username, doctor_id, session_id, session_title, dept, book_date, book_time, status)
      VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')
    ";
    $doctorId = (int)($sess["doctor_id"] ?? 0);
    $title = $sess["title"] ?? "Session";
    $dept  = $sess["dept"] ?? "";
    $date  = $sess["session_date"] ?? date("Y-m-d");
    $time  = $sess["session_time"] ?? "";

    $bst = mysqli_prepare($conn, $ins);
    mysqli_stmt_bind_param($bst, "siissss", $userEmail, $doctorId, $sessionId, $title, $dept, $date, $time);
    mysqli_stmt_execute($bst);
    mysqli_stmt_close($bst);
  }

  header("Location: scheduledSessions.php?booked=1");
  exit();
}

$sessions = [];
$q = trim($_GET["q"] ?? "");
$dept = trim($_GET["dept"] ?? "");

$sqlList = "
  SELECT s.id, s.title, s.dept, s.session_date, s.session_time, s.status,
         d.name AS doctor_name
  FROM sessions s
  LEFT JOIN doctors d ON s.doctor_id = d.id
  WHERE 1=1
";
$params = [];
$types = "";

if ($q !== "") {
  $sqlList .= " AND (s.title LIKE ? OR s.dept LIKE ? OR d.name LIKE ?)";
  $like = "%".$q."%";
  $params[] = $like; $params[] = $like; $params[] = $like;
  $types .= "sss";
}
if ($dept !== "") {
  $sqlList .= " AND s.dept = ?";
  $params[] = $dept;
  $types .= "s";
}
$sqlList .= " ORDER BY s.session_date ASC, s.session_time ASC";

$stList = mysqli_prepare($conn, $sqlList);
if (!empty($params)) mysqli_stmt_bind_param($stList, $types, ...$params);
mysqli_stmt_execute($stList);
$rsList = mysqli_stmt_get_result($stList);

while ($row = mysqli_fetch_assoc($rsList)) $sessions[] = $row;
mysqli_stmt_close($stList);

$departments = [];
$dr = mysqli_query($conn, "SELECT DISTINCT dept FROM sessions ORDER BY dept ASC");
if ($dr) while ($r = mysqli_fetch_assoc($dr)) $departments[] = $r["dept"];
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
        <div class="name"><?php echo htmlspecialchars($fullName); ?></div>
        <div class="email"><?php echo $safeEmail; ?></div>
      </div>
    </div>

    <a class="logoutBtn" href="logout.php">Log out</a>

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
        <p class="subTitle">Book available sessions from here.</p>

        <?php if (isset($_GET["booked"])): ?>
          <p style="margin-top:10px;font-weight:800;color:#16a34a;"> Booked! Check “My Bookings”.</p>
        <?php endif; ?>
      </div>
    </div>

    <form class="filterBar" method="GET" action="">
      <div class="searchBox">
        <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search session/doctor/department">
      </div>

      <select class="selectBox" name="dept">
        <option value="">All Departments</option>
        <?php foreach ($departments as $dp): ?>
          <option value="<?php echo htmlspecialchars($dp); ?>" <?php echo ($dept === $dp) ? "selected" : ""; ?>>
            <?php echo htmlspecialchars($dp); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button class="primaryBtn" type="submit">Filter</button>
      <a class="ghostBtn" href="scheduledSessions.php">Reset</a>
    </form>

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
          <?php if (count($sessions) === 0): ?>
            <tr><td colspan="7">No sessions found.</td></tr>
          <?php else: ?>
            <?php foreach ($sessions as $s): ?>
              <?php
                $status = $s["status"] ?? "Available";
                $statusClass = "statusGray";
                if ($status === "Available") $statusClass = "statusGreen";
                if ($status === "Booked") $statusClass = "statusAmber";
                if ($status === "Closed") $statusClass = "statusRed";
              ?>
              <tr>
                <td><?php echo htmlspecialchars($s["title"]); ?></td>
                <td><?php echo htmlspecialchars($s["doctor_name"] ?? "Unknown"); ?></td>
                <td><?php echo htmlspecialchars($s["dept"]); ?></td>
                <td><?php echo htmlspecialchars($s["session_date"]); ?></td>
                <td><?php echo htmlspecialchars($s["session_time"]); ?></td>
                <td><span class="status <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                <td>
                  <?php if ($status === "Available"): ?>
                    <form method="POST" action="" style="margin:0;">
                      <input type="hidden" name="book_session_id" value="<?php echo (int)$s["id"]; ?>">
                      <button class="bookBtn" type="submit">Book</button>
                    </form>
                  <?php else: ?>
                    <span class="muted">—</span>
                  <?php endif; ?>
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
