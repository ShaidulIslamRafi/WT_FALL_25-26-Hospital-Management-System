<?php
session_start();
require_once "php/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["username"] ?? "");
  $password = trim($_POST["password"] ?? "");

  if ($email === "" || $password === "") {
    $error = "Please enter email and password";
  } else {
    $stmt = mysqli_prepare($conn, "SELECT id, email, role FROM users WHERE email = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($user) {
      $_SESSION["username"] = $user["email"];
      $_SESSION["user_id"] = (int)$user["id"];
      $_SESSION["role"] = $user["role"];

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
  <input type="text" name="username" placeholder="Enter Email" style="top:35%;"><br><br>

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
    min-height:18px;   /* keeps bottom section visible */
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
