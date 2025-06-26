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
  } else if (isset($_POST['flag']) && $_POST['flag'] === 'addRecord') {
    var_dump($_POST);
    var_dump($_FILES);

    // Pet Variables
    $petName = ucfirst(htmlspecialchars($_POST["petname"]));
    $species = ucfirst(htmlspecialchars($_POST["species"]));
    $breed = ucfirst(htmlspecialchars($_POST["breed"]));
    $color = ucfirst(htmlspecialchars($_POST["color"]));
    $lastSeenLocation = ucfirst(htmlspecialchars($_POST["lastSeenLocation"]));
    $lastSeenDate = htmlspecialchars($_POST["lastSeenDate"]);
    if (empty($_FILES)) {
      $photoUrl = null;
      $photoExt = null;
    } else {
      $photoUrl = "petLost";
      $photoExt = $_POST["imageExt"];
    }
    // echo $photoUrl;
    // echo $photoExt;
    $reportStatus = "Pending";

    // Owner Variables
    $ownerId = intval(htmlspecialchars($_POST["ownerId"]));
    $ownerName = ucfirst(htmlspecialchars($_POST["ownerName"]));
    $contactNumber = htmlspecialchars($_POST["contactNumber"]);
    $email = htmlspecialchars($_POST["email"]);

    // Will use a transaction here
    $conn->begin_transaction();
    try {
      // we insert the owner first because without an owner, a lostReport cannot exist
      if ($ownerId === 0) { // if the ownerId is not 0, that means the owner already exists
        $query = "INSERT INTO owners (Name, ContactNumber, Email) Values (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $ownerName, $contactNumber, $email);
        $stmt->execute();
        // After owner is inserted we retrive the ownerId that is generated automatically. we update the $ownerId with retrived id
        $ownerId = $conn->insert_id;
        $stmt->close();
      }
      echo $ownerId;

      // After successful owner insertion, We insert the record
      $query = "INSERT INTO lostreport (OwnerID, PetName, Species, Breed, Color, LastSeenLocation, LastSeenDate, PhotoURL, ReportStatus) Values (?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("issssssss", $ownerId, $petName, $species, $breed, $color, $lastSeenLocation, $lastSeenDate, $photoUrl, $reportStatus);
      $stmt->execute();
      $reportId = $conn->insert_id;
      $stmt->close();

      if ($photoUrl !== null) {
        // Updating the photo url, adding the reportId and the extension at the end.
        $updatedUrl = $photoUrl . $reportId . $photoExt;
        $query = "UPDATE lostreport SET PhotoURL = ? WHERE ReportID = ?";
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

      // Finally we link the report with the currently logged-in user
      $finderReportId = null;
      $query = "INSERT INTO usersreportslink (UserName, LostReportID, FinderReportID) Values (?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sii", $username, $reportId, $finderReportId);
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
