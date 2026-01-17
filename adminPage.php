<?php
session_start();
if (!isset($_SESSION["username"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: loginPage.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Area</title>
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body style="padding:30px;">
  <h1>Admin Page (temporary)</h1>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?> ✅</p>
  <p>We will merge the real Admin dashboard from GitHub later.</p>
  <p><a href="logout.php">Logout</a></p>
</body>
</html>
