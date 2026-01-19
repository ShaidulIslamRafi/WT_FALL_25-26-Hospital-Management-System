<?php
session_start();
if(!isset($_SESSION["username"])){
  header("Location: ../loginPage.php");
  exit;
}

if(isset($_SESSION["roles"]) && $_SESSION["roles"] !== "admin"){
  header("Location: ../loginPage.php");
  exit;
}
include "../database.php";

$id = $_GET["id"] ?? "";
if($id=="") die("Missing id");

$stmt = $conn->prepare("DELETE FROM session WHERE sessiontitle=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: ../schedulePage.php");
exit;
