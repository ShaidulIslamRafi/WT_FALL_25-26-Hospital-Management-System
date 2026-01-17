<?php
session_start();
require_once "php/db.php";

$error = "";

$fname = $_POST["fname"] ?? "";
$lname = $_POST["lname"] ?? "";
$age = $_POST["age"] ?? "";
$bgroup = $_POST["bgroup"] ?? "";
$uname = $_POST["uname"] ?? "";
$role = $_POST["role"] ?? "patient";
$password = $_POST["password"] ?? "";
$cpassword = $_POST["cpassword"] ?? "";

function clean($v){ return trim($v); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $fname = clean($fname);
  $lname = clean($lname);
  $age = clean($age);
  $bgroup = clean($bgroup);
  $uname = clean($uname);
  $password = clean($password);
  $cpassword = clean($cpassword);
  $role = clean($role);

  if ($role !== "admin" && $role !== "patient") {
    $role = "patient";
  }

  if ($fname === "" || $lname === "" || $age === "" || $bgroup === "" || $uname === "" || $password === "" || $cpassword === "") {
    $error = "All fields must be filled.";
  }
  elseif (!filter_var($uname, FILTER_VALIDATE_EMAIL)) {
    $error = "Please enter a valid email address.";
  }
  elseif (!ctype_digit($age) || $age < 1 || $age > 120) {
    $error = "Please enter a valid age.";
  }
  elseif ($password !== $cpassword) {
    $error = "Passwords do not match.";
  }
  else {
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
    mysqli_stmt_bind_param($check, "s", $uname);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if (mysqli_fetch_assoc($res)) {
      $error = "Email already registered. Please login.";
    } else {
      $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users (fname, lname, age, bgroup, email, password, role)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
      );
      mysqli_stmt_bind_param($stmt, "ssissss",
        $fname, $lname, $age, $bgroup, $uname, $password, $role
      );

      if (mysqli_stmt_execute($stmt)) {
        header("Location: loginPage.php?registered=1");
        exit();
      } else {
        $error = "Registration failed. Try again.";
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="css/registration.css">
</head>
<body>

<form method="post" action="">
  <h1>Registration</h1>

  <?php if ($error !== ""): ?>
    <div class="error"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <label>First Name</label>
  <input type="text" name="fname" value="<?php echo htmlspecialchars($fname); ?>">

  <label>Last Name</label>
  <input type="text" name="lname" value="<?php echo htmlspecialchars($lname); ?>">

  <label>Age</label>
  <input type="text" name="age" value="<?php echo htmlspecialchars($age); ?>">

  <label>Blood Group</label>
  <input type="text" name="bgroup" value="<?php echo htmlspecialchars($bgroup); ?>">

  <label>Email</label>
  <input type="email" name="uname" value="<?php echo htmlspecialchars($uname); ?>">

  <label>Role</label>
  <select name="role">
    <option value="patient" <?php if($role==="patient") echo "selected"; ?>>Patient</option>
    <option value="admin" <?php if($role==="admin") echo "selected"; ?>>Admin</option>
  </select>

  <label>Password</label>
  <input type="password" name="password">

  <label>Confirm Password</label>
  <input type="password" name="cpassword">

  <input type="submit" value="Submit">

  <div class="form-footer">
    Already have an account?
    <a href="loginPage.php">Login</a>
  </div>
</form>

</body>
</html>
