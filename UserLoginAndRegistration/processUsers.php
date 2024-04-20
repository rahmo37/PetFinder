<?php
require_once '../login.php';
// Establishing connection with database
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
  die("Fatal Error");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST["username"]) && isset($_POST["email"])) {
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $query = "SELECT 1 FROM users WHERE username = ?"; // This query will return 1 for each row that matches the username
    
  }
}
