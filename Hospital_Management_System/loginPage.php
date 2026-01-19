<?php
session_start();
require_once "php/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");
  $password = trim($_POST["password"] ?? "");

  if ($email === "" || $password === "") {
    $error = "Please enter email and password";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email address";
  } else {
    // ✅ login by EMAIL + PASSWORD
    $stmt = mysqli_prepare($conn, "SELECT id, fname, lname, email, role, password FROM users WHERE email = ? LIMIT 1");
    if (!$stmt) {
      die("Query error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($user && $user["password"] === $password) {
      // ✅ sessions
      $_SESSION["user_id"] = (int)$user["id"];
      $_SESSION["email"]   = $user["email"];
      $_SESSION["role"]    = $user["role"];

      // optional display name
      $_SESSION["display_name"] = trim(($user["fname"] ?? "") . " " . ($user["lname"] ?? ""));

      if ($user["role"] === "admin") {
        header("Location: adminPage.php");
      } else {
        header("Location: dashboard.php");
      }
      exit();
    } else {
      $error = "Invalid email or password";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Log In</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

<form method="POST" action="">
  <h1>Login Page</h1>
  <p>Login with your details to continue</p>

  <?php if (isset($_GET["registered"])): ?>
    <p style="color:green; text-align:center; margin-top:10px;">
      Registration successful. Please login.
    </p>
  <?php else: ?>
    <p style="visibility:hidden; text-align:center; margin-top:10px;">.</p>
  <?php endif; ?>

  <label style="top:30%;">Email:</label><br>
  <input type="text" name="email" placeholder="Enter Email" style="top:35%;" value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"><br><br>

  <label style="top:45%;">Password:</label><br>
  <input type="password" name="password" placeholder="Password" style="top:50%;"><br><br>

  <button type="submit" style="top:63%;">Login</button>

  <p style="
    position:absolute;
    top:71%;
    left:0;
    width:100%;
    text-align:center;
    color:#dc2626;
    font-size:14px;
    font-weight:600;
    margin:0;
    min-height:18px;
  ">
    <?php echo ($error !== "") ? htmlspecialchars($error) : ""; ?>
  </p>

  <label style="position:absolute; top:80%; left:0; width:100%; text-align:center;">
    Don’t have an account?
    <a href="registrationPage.php">Register</a>
  </label>

</form>

</body>
</html>
