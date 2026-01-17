<?php
session_start();
if (!isset($_SESSION["username"])) {
  header("Location: loginPage.php");
  exit();
}
$username = htmlspecialchars($_SESSION["username"]);

$doctors = [
  ["name"=>"Dr. Sarah Ahmed", "dept"=>"Cardiology", "title"=>"Consultant", "status"=>"Available", "fee"=>"৳800", "time"=>"10:00 AM - 02:00 PM", "rating"=>4.8],
  ["name"=>"Dr. Hasan Rahman", "dept"=>"Medicine", "title"=>"Specialist", "status"=>"In Session", "fee"=>"৳600", "time"=>"02:00 PM - 06:00 PM", "rating"=>4.6],
  ["name"=>"Dr. Nabila Sultana", "dept"=>"Gynecology", "title"=>"Consultant", "status"=>"Available", "fee"=>"৳900", "time"=>"09:00 AM - 01:00 PM", "rating"=>4.9],
  ["name"=>"Dr. Imran Hossain", "dept"=>"Orthopedics", "title"=>"Surgeon", "status"=>"Offline", "fee"=>"৳1000", "time"=>"—", "rating"=>4.5],
  ["name"=>"Dr. Farzana Islam", "dept"=>"Dermatology", "title"=>"Specialist", "status"=>"Available", "fee"=>"৳700", "time"=>"04:00 PM - 08:00 PM", "rating"=>4.7],
  ["name"=>"Dr. Mahmudul Hasan", "dept"=>"Pediatrics", "title"=>"Consultant", "status"=>"Available", "fee"=>"৳750", "time"=>"11:00 AM - 03:00 PM", "rating"=>4.6],
];

$q = strtolower(trim($_GET["q"] ?? ""));
$deptFilter = trim($_GET["dept"] ?? "");

$filtered = array_filter($doctors, function($d) use ($q, $deptFilter) {
  $matchQ = ($q === "") ||
    (strpos(strtolower($d["name"]), $q) !== false) ||
    (strpos(strtolower($d["dept"]), $q) !== false) ||
    (strpos(strtolower($d["title"]), $q) !== false);

  $matchDept = ($deptFilter === "") || ($d["dept"] === $deptFilter);
  return $matchQ && $matchDept;
});

$departments = array_values(array_unique(array_map(fn($d) => $d["dept"], $doctors)));
sort($departments);
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
        <a class="menuItem" href="dashboard.php">
          <i class="fa-solid fa-house"></i><span>Home</span>
        </a>

        <a class="menuItem active" href="doctorPage.php">
          <i class="fa-solid fa-user-doctor"></i><span>All Doctors</span>
        </a>

        <a class="menuItem" href="scheduledSessions.php">
          <i class="fa-regular fa-calendar"></i><span>Scheduled Sessions</span>
        </a>

        <a class="menuItem" href="myBookings.php">
  <span>My Bookings</span>
</a>


       <a class="menuItem" href="settings.php">Settings</a>

      </nav>
    </aside>

    <main class="main">
      <div class="topbar">
        <div>
          <h1 class="pageTitle">All Doctors</h1>
          <p class="subTitle">Search and filter doctors by department, status, and availability.</p>
        </div>

        <div class="topRight">
          <div class="userChip">
            <i class="fa-regular fa-bell"></i>
            <span class="userName"><?php echo $username; ?></span>
            <i class="fa-solid fa-circle-user"></i>
          </div>
        </div>
      </div>

      <form class="filterBar" method="GET" action="">
        <div class="searchBox">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input
            type="text"
            name="q"
            value="<?php echo htmlspecialchars($_GET["q"] ?? ""); ?>"
            placeholder="Search by name, department, title..."
          />
        </div>

        <select class="selectBox" name="dept">
          <option value="">All Departments</option>
          <?php foreach ($departments as $dep): ?>
            <option value="<?php echo htmlspecialchars($dep); ?>"
              <?php echo ($deptFilter === $dep) ? "selected" : ""; ?>>
              <?php echo htmlspecialchars($dep); ?>
            </option>
          <?php endforeach; ?>
        </select>

        <button class="primaryBtn" type="submit">
          <i class="fa-solid fa-filter"></i> Filter
        </button>

        <a class="ghostBtn" href="doctorPage.php">Reset</a>
      </form>
    
      <div class="infoRow">
        <div class="pill">
          <span class="dot dotPurple"></span>
          <span>Total:</span>
          <b><?php echo count($filtered); ?></b>
        </div>

        <div class="hint">
          Tip: Later you can connect database and click “Book” to create appointment.
        </div>
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
              <div class="docAvatar">
                <i class="fa-solid fa-user-doctor"></i>
              </div>

              <div class="cardMeta">
                <h3 class="docName"><?php echo htmlspecialchars($d["name"]); ?></h3>
                <p class="docSub">
                  <?php echo htmlspecialchars($d["title"]); ?> • <?php echo htmlspecialchars($d["dept"]); ?>
                </p>
              </div>

              <span class="chip <?php echo $statusClass; ?>">
                <?php echo htmlspecialchars($status); ?>
              </span>
            </div>

            <div class="cardBody">
              <div class="statRow">
                <div class="stat">
                  <div class="statLabel">Rating</div>
                  <div class="statValue">
                    <i class="fa-solid fa-star"></i> <?php echo htmlspecialchars($d["rating"]); ?>
                  </div>
                </div>
                <div class="stat">
                  <div class="statLabel">Consultation</div>
                  <div class="statValue"><?php echo htmlspecialchars($d["fee"]); ?></div>
                </div>
                <div class="stat">
                  <div class="statLabel">Time</div>
                  <div class="statValue"><?php echo htmlspecialchars($d["time"]); ?></div>
                </div>
              </div>

              <div class="actions">
                <button class="secondaryBtn" type="button">
                  <i class="fa-regular fa-comment-dots"></i> Message
                </button>
                <button class="bookBtn" type="button">
                  <i class="fa-regular fa-calendar-check"></i> Book
                </button>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </section>

    </main>

  </div>

</body>
</html>
