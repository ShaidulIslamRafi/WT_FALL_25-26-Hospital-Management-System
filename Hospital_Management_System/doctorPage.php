<?php
session_start();
require_once "php/db.php";


if (!isset($_SESSION["email"])) {
  header("Location: loginPage.php");
  exit();
}

$email = $_SESSION["email"];
$safeEmail = htmlspecialchars($email);


$ustmt = mysqli_prepare($conn, "SELECT fname, lname, email FROM users WHERE email = ? LIMIT 1");
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

$fullName = trim(($user["fname"] ?? "") . " " . ($user["lname"] ?? ""));
if ($fullName === "") $fullName = "Patient";
$safeName = htmlspecialchars($fullName);
$userEmail = $user["email"] ?? $email;
$safeUserEmail = htmlspecialchars($userEmail);



if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["book_doctor_id"])) {
  $doctorId = (int)$_POST["book_doctor_id"];

  $dstmt = mysqli_prepare($conn, "SELECT name, dept, time_slot FROM doctors WHERE id=? LIMIT 1");
  mysqli_stmt_bind_param($dstmt, "i", $doctorId);
  mysqli_stmt_execute($dstmt);
  $dres = mysqli_stmt_get_result($dstmt);
  $doc = mysqli_fetch_assoc($dres);
  mysqli_stmt_close($dstmt);

  if ($doc) {
    $bookDate = date("Y-m-d");
    $bookTime = ($doc["time_slot"] && $doc["time_slot"] !== "—") ? $doc["time_slot"] : "10:00 AM - 12:00 PM";
    $sessionTitle = "Doctor Appointment";
    $dept = $doc["dept"];


    $bstmt = mysqli_prepare($conn, "
      INSERT INTO bookings (username, doctor_id, session_title, dept, book_date, book_time, status)
      VALUES (?, ?, ?, ?, ?, ?, 'Pending')
    ");
    mysqli_stmt_bind_param($bstmt, "sissss", $email, $doctorId, $sessionTitle, $dept, $bookDate, $bookTime);
    mysqli_stmt_execute($bstmt);
    mysqli_stmt_close($bstmt);
  }

  header("Location: doctorPage.php?booked=1");
  exit();
}



$q = trim($_GET["q"] ?? "");
$deptFilter = trim($_GET["dept"] ?? "");


$departments = [];
$deptRes = mysqli_query($conn, "SELECT DISTINCT dept FROM doctors ORDER BY dept ASC");
if ($deptRes) {
  while ($row = mysqli_fetch_assoc($deptRes)) {
    $departments[] = $row["dept"];
  }
}


$sql = "SELECT id, name, dept, title, status, fee, time_slot, rating FROM doctors WHERE 1=1";
$params = [];
$types = "";

if ($q !== "") {
  $sql .= " AND (name LIKE ? OR dept LIKE ? OR title LIKE ?)";
  $like = "%".$q."%";
  $params[] = $like; $params[] = $like; $params[] = $like;
  $types .= "sss";
}
if ($deptFilter !== "") {
  $sql .= " AND dept = ?";
  $params[] = $deptFilter;
  $types .= "s";
}
$sql .= " ORDER BY name ASC";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) die("Query prepare failed: " . mysqli_error($conn));
if (!empty($params)) mysqli_stmt_bind_param($stmt, $types, ...$params);

mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$filtered = [];
while ($row = mysqli_fetch_assoc($res)) $filtered[] = $row;

mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>All Doctors</title>

  <link rel="stylesheet" href="css/all-doctors.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="app">

  <aside class="sidebar">
  


    <div class="profileBox">
      <div class="avatar"><i class="fa-regular fa-user"></i></div>
      <div class="profileText">
        <div class="name"><?php echo $safeName; ?></div>
        <div class="email"><?php echo $safeUserEmail; ?></div>
      </div>
    </div>

    <a class="logoutBtn" href="loginPage.php">Log out</a>

    <nav class="menu">
      <a class="menuItem" href="dashboard.php"><i class="fa-solid fa-house"></i><span>Home</span></a>
      <a class="menuItem active" href="doctorPage.php"><i class="fa-solid fa-user-doctor"></i><span>All Doctors</span></a>
      <a class="menuItem" href="scheduledSessions.php"><i class="fa-regular fa-calendar"></i><span>Scheduled Sessions</span></a>
      <a class="menuItem" href="myBookings.php"><i class="fa-regular fa-bookmark"></i><span>My Bookings</span></a>
      <a class="menuItem" href="settings.php"><i class="fa-solid fa-gear"></i><span>Settings</span></a>
    </nav>
  </aside>

  <main class="main">

    <div class="topbar">
      <div>
        <h1 class="pageTitle">All Doctors</h1>
        <p class="subTitle">Search and filter doctors by department, status, and availability.</p>

        <?php if (isset($_GET["booked"])): ?>
          <p style="margin-top:10px;font-weight:700;color:#16a34a;"> Booking added! Check “My Bookings”.</p>
        <?php endif; ?>
      </div>

      <div class="topRight">
        <div class="userChip">
          <i class="fa-regular fa-bell"></i>
          <span class="userName"><?php echo $safeName; ?></span>
          <i class="fa-solid fa-circle-user"></i>
        </div>
      </div>
    </div>

    <form class="filterBar" method="GET" action="">
      <div class="searchBox">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search by name, department, title..." />
      </div>

      <select class="selectBox" name="dept">
        <option value="">All Departments</option>
        <?php foreach ($departments as $dep): ?>
          <option value="<?php echo htmlspecialchars($dep); ?>" <?php echo ($deptFilter === $dep) ? "selected" : ""; ?>>
            <?php echo htmlspecialchars($dep); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button class="primaryBtn" type="submit"><i class="fa-solid fa-filter"></i> Filter</button>
      <a class="ghostBtn" href="doctorPage.php">Reset</a>
    </form>

    <div class="infoRow">
      <div class="pill">
        <span class="dot dotPurple"></span>
        <span>Total:</span>
        <b><?php echo count($filtered); ?></b>
      </div>
      <div class="hint">Doctors loaded from database.</div>
    </div>

    <section class="grid">
      <?php if (count($filtered) === 0): ?>
        <div class="empty">
          <div class="emptyIcon"><i class="fa-regular fa-face-frown"></i></div>
          <h3>No doctors found</h3>
          <p>Try changing the search text or selecting a different department.</p>
        </div>
      <?php endif; ?>

      <?php foreach ($filtered as $d): ?>
        <?php
          $status = $d["status"];
          $statusClass = "chipGray";
          if ($status === "Available") $statusClass = "chipGreen";
          if ($status === "In Session") $statusClass = "chipAmber";
          if ($status === "Offline") $statusClass = "chipGray";
        ?>

        <article class="card">
          <div class="cardTop">
            <div class="docAvatar"><i class="fa-solid fa-user-doctor"></i></div>

            <div class="cardMeta">
              <h3 class="docName"><?php echo htmlspecialchars($d["name"]); ?></h3>
              <p class="docSub"><?php echo htmlspecialchars($d["title"]); ?> • <?php echo htmlspecialchars($d["dept"]); ?></p>
            </div>

            <span class="chip <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span>
          </div>

          <div class="cardBody">
            <div class="statRow">
              <div class="stat"><div class="statLabel">Rating</div><div class="statValue"><i class="fa-solid fa-star"></i> <?php echo htmlspecialchars($d["rating"]); ?></div></div>
              <div class="stat"><div class="statLabel">Consultation</div><div class="statValue"><?php echo htmlspecialchars($d["fee"]); ?></div></div>
              <div class="stat"><div class="statLabel">Time</div><div class="statValue"><?php echo htmlspecialchars($d["time_slot"]); ?></div></div>
            </div>

            <div class="actions">
              <button class="secondaryBtn" type="button"><i class="fa-regular fa-comment-dots"></i> Message</button>

              <form method="POST" action="" style="margin:0;">
                <input type="hidden" name="book_doctor_id" value="<?php echo (int)$d["id"]; ?>">
                <button class="bookBtn" type="submit">
                  <i class="fa-regular fa-calendar-check"></i> Book
                </button>
              </form>
            </div>

          </div>
        </article>
      <?php endforeach; ?>
    </section>

  </main>
</div>

</body>
</html>
