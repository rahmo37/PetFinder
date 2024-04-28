<?php
require_once '../../login.php';
// Establishing connection with database
$conn = new mysqli($hn, $un, $pw, $db);
// If Connection fails program ends
if ($conn->connect_error) {
  die("Fatal Error");
}

session_start();
if (!isset($_SESSION['username'])) {
  echo '
  <style>
  #backBtn {
    display: none;
  }
  </style>
  <div">
  <h1 id="welcomeTitle">Page Not Found.</h1>
  <h2 style="color: white; text-shadow: 2px 2px 4px #000000;">Please click the below link to go to the login page</h2>
  <a href="../../UserLoginAndRegistration/login.html" class="menu-button">Login Page</a>
  </div>';
  die();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['flag']) && $_POST['flag'] === 'checkContactNumberAndEmail') {
    $ownerName = htmlspecialchars($_POST["ownerName"]);
    $contactNumber = htmlspecialchars($_POST["contactNumber"]);
    $email = htmlspecialchars($_POST["email"]);
    $ownerId = 0;

    $query = "SELECT ownerId, name, email FROM owners WHERE contactNumber = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $contactNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    // echo ($owneName . $contactNumber . $email);

    if ($result->num_rows > 0) {

      while ($row = $result->fetch_assoc()) {
        if (strtolower($row['name']) === strtolower($ownerName) && strtolower($row['email']) === strtolower($email)) {

          $ownerId = $row['ownerId'];
          exit($ownerId . "");
          break;
        }
      }
      if ($ownerId === 0) {
        exit("invalid");
      }
    } else {
      exit("unique");
    }
  } else if (isset($_POST['flag']) && $_POST['flag'] === 'checkEmail') {
    $email = htmlspecialchars($_POST['email']);
    $query = "SELECT 1 FROM owners WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      exit("invalid");
    } else {
      exit("unique");
    }
  }
}
