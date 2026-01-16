<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Demo login: allow any non-empty username/password
    if ($username !== "" && $password !== "") {
        $_SESSION["username"] = $username;

        // Redirect to your actual dashboard file name
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Please enter username and password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Hospital management">
  <meta name="author" content="Rafi">
  <meta name="keyword" content="Hospital,management">
  <meta name="viewport" content ="width=device-width,initial-scale=1.0">

  <title>Log In</title>
  <link rel="stylesheet" href="css/login.css">
</head>

<body>

  <form method="POST" action="">
    <h1>Login Page</h1>
    <p>Login with your details to continue </p>

    <?php if (!empty($error)) { ?>
      <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <label>Username:</label><br>
    <input type="text" name="username" placeholder="UserName"><br><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" placeholder="Password"><br><br>

    <button type="submit">Login</button>

    <label style="position:absolute;top:80%;">Don`t have an account</label>
  </form>

</body>
</html>
