<?php
session_start();

if (!isset($_SESSION["username"])) {
  header("Location: loginPage.php");
  exit();
}

if (isset($_SESSION["role"]) && $_SESSION["role"] !== "patient") {
  header("Location: adminPage.php"); 
  exit();
}
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
          <div class="name"><?php echo htmlspecialchars($_SESSION["username"]); ?></div>
          <div class="email">patient@edoc.com</div>
        </div>
      </div>

      <a class="logoutBtn" href="loginPage.php">Log out</a>

      <nav class="menu">
        <a class="menuItem active" href="dashboard.php">
          <i class="fa-solid fa-house"></i>
          <span>Home</span>
        </a>

        <a class="menuItem" href="doctorPage.php">
          <i class="fa-regular fa-square-plus"></i>
          <span>All Doctors</span>
        </a>

        <a class="menuItem" href="scheduledSessions.php">
          <i class="fa-regular fa-calendar"></i>
          <span>Scheduled Sessions</span>
        </a>

        <a class="menuItem" href="myBookings.php">
          <i class="fa-regular fa-bookmark"></i>
          <span>My Bookings</span>
        </a>

        <a class="menuItem" href="settings.php">
          <i class="fa-solid fa-gear"></i>
          <span>Settings</span>
        </a>
      </nav>
    </aside>

    <main class="main">

      <div class="topbar">
        <h1 class="pageTitle">Home</h1>

        <div class="dateBox">
          <div class="dateText">
            <div class="small">Today's Date</div>
            <div class="big" id="todayDate">2022-06-03</div>
          </div>
          <button class="calBtn" type="button" title="Calendar">
            <i class="fa-regular fa-calendar"></i>
          </button>
        </div>
      </div>

      <section class="hero">
        <div class="heroText">
          <div class="welcome">Welcome!</div>
          <div class="heroName"><?php echo htmlspecialchars($_SESSION["username"]); ?>.</div>

          <p class="heroDesc">
            Haven't any idea about doctors? no problem let's jumping to "<b>All Doctors</b>" section or "<b>Sessions</b>".
            Track your past and future appointments history.<br>
            Also find out the expected arrival time of your doctor or medical consultant.
          </p>

          <div class="heroSearchTitle">Channel a Doctor Here</div>

          <div class="searchRow">
            <div class="searchInput">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="text" placeholder="Search Doctor and We will Find The Session Available">
            </div>
            <button class="searchBtn" type="button">Search</button>
          </div>
        </div>

        <div class="heroImage" aria-hidden="true"></div>
      </section>

      <section class="lower">
        <div class="leftCol">
          <h2 class="sectionTitle">Status</h2>

          <div class="statusGrid">
            <div class="statusCard">
              <div class="num">1</div>
              <div class="label">All Doctors</div>
              <div class="icon"><i class="fa-solid fa-user-doctor"></i></div>
            </div>

            <div class="statusCard">
              <div class="num">2</div>
              <div class="label">All Patients</div>
              <div class="icon"><i class="fa-solid fa-wheelchair"></i></div>
            </div>

            <div class="statusCard">
              <div class="num">1</div>
              <div class="label">NewBooking</div>
              <div class="icon"><i class="fa-regular fa-bookmark"></i></div>
            </div>

            <div class="statusCard">
              <div class="num">0</div>
              <div class="label">Today Sessions</div>
              <div class="icon"><i class="fa-regular fa-calendar-check"></i></div>
            </div>
          </div>
        </div>

        <div class="rightCol">
          <h2 class="sectionTitle">Your Upcoming Booking</h2>

          <div class="tableCard">
            <table>
              <thead>
                <tr>
                  <th>Appoint. Number</th>
                  <th>Session Title</th>
                  <th>Doctor</th>
                  <th>Scheduled Date &amp; Time</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="bigNum">1</td>
                  <td>Test Session</td>
                  <td>Test Doctor</td>
                  <td>2050-01-01 18:00</td>
                </tr>
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
