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
} else {
  $username = $_SESSION['username'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['flag']) && $_POST['flag'] === 'checkContactNumberAndEmail') {
    $founderName = htmlspecialchars($_POST["founderName"]);
    $contactNumber = htmlspecialchars($_POST["contactNumber"]);
    $email = htmlspecialchars($_POST["email"]);
    $founderId = 0;

    $query = "SELECT finderId, name, email FROM finder WHERE contactNumber = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $contactNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

      while ($row = $result->fetch_assoc()) {
        if (strtolower($row['name']) === strtolower($founderName) && strtolower($row['email']) === strtolower($email)) {

          $founderId = $row['finderId'];
          exit($founderId . "");
          break;
        }
      }
      if ($founderId === 0) {
        exit("invalid");
      }
    } else {
      exit("unique");
    }
  } else if (isset($_POST['flag']) && $_POST['flag'] === 'checkEmail') {
    $email = htmlspecialchars($_POST['email']);
    $query = "SELECT 1 FROM finder WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      exit("invalid");
    } else {
      exit("unique");
    }
  } else if (isset($_POST['flag']) && $_POST['flag'] === 'addRecord') {
    var_dump($_POST);
    var_dump($_FILES);

    // Pet Variables
    $species = ucfirst(htmlspecialchars($_POST["species"]));
    $breed = ucfirst(htmlspecialchars($_POST["breed"]));
    $color = ucfirst(htmlspecialchars($_POST["color"]));
    $foundLocation = ucfirst(htmlspecialchars($_POST["foundLocation"]));
    $foundDate = htmlspecialchars($_POST["foundDate"]);
    if (empty($_FILES)) {
      $photoUrl = null;
      $photoExt = null;
    } else {
      $photoUrl = "petFound";
      $photoExt = $_POST["imageExt"];
    }
    // echo $photoUrl;
    // echo $photoExt;
    $reportStatus = "Pending";

    // Founder Variables
    $founderId = intval(htmlspecialchars($_POST["founderId"]));
    $founderName = ucfirst(htmlspecialchars($_POST["founderName"]));
    $contactNumber = htmlspecialchars($_POST["contactNumber"]);
    $email = htmlspecialchars($_POST["email"]);

    // Will use a transaction here
    $conn->begin_transaction();
    try {
      // we insert the founder first because without a founder, a founderReport cannot exist
      if ($founderId === 0) { // if the founderId is not 0, that means the founder already exists
        $query = "INSERT INTO finder (Name, ContactNumber, Email) Values (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $founderName, $contactNumber, $email);
        $stmt->execute();
        // After founder is inserted we retrive the founder that is generated automatically. we update the $founderId with retrived id
        $founderId = $conn->insert_id;
        $stmt->close();
      }
      echo $founderId;

      // After successful founder insertion, We insert the record finderreports
      $query = "INSERT INTO finderreports (FinderID, Species, Breed, Color, FoundLocation, FoundDate, PhotoURL, ReportStatus) Values (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("isssssss", $founderId, $species, $breed, $color, $foundLocation, $foundDate, $photoUrl, $reportStatus);
      $stmt->execute();
      $reportId = $conn->insert_id;
      $stmt->close();

      if ($photoUrl !== null) {
        // Updating the photo url, adding the reportId and the extension at the end.
        $updatedUrl = $photoUrl . $reportId . $photoExt;
        $query = "UPDATE finderreports SET PhotoURL = ? WHERE ReportID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $updatedUrl, $reportId);
        $stmt->execute();
        $stmt->close();

        // Now i save the actual image in the server
        $imageDirectory = "../../images/";
        $imageFullPath = $imageDirectory . $updatedUrl;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imageFullPath)) {
          throw new Exception("Error uploading file in the server");
        }
      }

      // Finally we connect the report with the currently logged-in user
      $lostReportID = null;
      $query = "INSERT INTO usersreportslink (UserName, LostReportID, FinderReportID) Values (?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sii", $username, $lostReportID, $reportId);
      $stmt->execute();
      $stmt->close();

      // Finally we commit
      $conn->commit();
    } catch (Exception $e) {
      // Rollback on any error
      $conn->rollback();
      echo "Failed";
    }
  }
}
