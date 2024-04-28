<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./userLostReports.css">
  <title>Document</title>
</head>

<body>
  <?php
  require_once '../../login.php';
  // Establishing connection with database
  $conn = new mysqli($hn, $un, $pw, $db);
  // If Connection fails program ends
  if ($conn->connect_error) {
    die("Fatal Error");
  }

  viewYourReports($conn);

  function viewYourReports($conn)
  {
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
      $username = htmlspecialchars($_SESSION['username']);
    }

    echo '<p id="title">Your Current Reports</p>';
    // Building the query for viewing lost reports
    $query = "SELECT 
    l.PetName AS petName,
    o.Name AS ownerName,
    o.ContactNumber AS phoneNumber,
    o.Email AS email,
    l.Breed AS breed,
    l.Species AS species,
    l.Color AS color,
    l.ReportDate AS rDate,
    l.ReportStatus AS rStatus,
    l.LastSeenLocation AS lastSeenLocation,
    l.LastSeenDate AS lastSeenDate,
    l.PhotoURL AS photoURL
FROM 
    UsersReportsLink ul
JOIN 
    LostReport l ON ul.LostReportID = l.ReportID
JOIN 
    Owners o ON l.OwnerID = o.OwnerID
WHERE 
    ul.UserName = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();

    $result = $stmt->get_result();

    for ($i = 0; $i < $result->num_rows; $i++) {
      $row = $result->fetch_assoc();
      $petName = htmlspecialchars($row['petName']);
      $ownerName = htmlspecialchars($row['ownerName']);
      $phoneNumber = htmlspecialchars($row['phoneNumber']);
      $email = htmlspecialchars($row['email']);
      $breed = htmlspecialchars($row['breed']);
      $species = htmlspecialchars($row['species']);
      $color = htmlspecialchars($row['color']);
      $rDate = htmlspecialchars($row['rDate']);
      $status = htmlspecialchars($row['rStatus']);
      $lastSeenLocation = htmlspecialchars($row['lastSeenLocation']);
      $lastSeenDate = htmlspecialchars($row['lastSeenDate']);
      $PhotoURL = !htmlspecialchars($row['photoURL']) ? "NoImage.jpg" : htmlspecialchars($row['photoURL']);

      $statusColor = $status === "Accepted" ? "#37ea18" : "#ff7f50";

      $htmlContent = <<<HTML
        <div class="container">
          <div class="img-name-container">
            <img src="../../Images/$PhotoURL" alt="" id="petImage" />
            <div class="name-container">
              <p id="petName">$petName</p>
            </div>
          </div>
          <div class="pet-info-container">
            <div class="info">
              <label>Breed</label>
              <p>$breed</p>
            </div>
            <div class="info">
              <label>Color</label>
              <p>$color</p>
            </div>
            <div class="info">
              <label>Species</label>
              <p>$species</p>
            </div>
            <div class="info">
              <label>Last Seen Location</label>
              <p>$lastSeenLocation</p>
            </div>
            <div class="info">
              <label>Last Seen Date</label>
              <p>$lastSeenDate</p>
            </div>
            <div class="info">
              <label>Report Date</label>
              <p>$rDate</p>
            </div>
          </div>
          <hr>
          <div class="pet-info-container">
            <div class="info">
              <label>Owner</label>
              <p>$ownerName</p>
            </div>
            <div class="info">
              <label>Phone</label>
              <p>$phoneNumber</p>
            </div>
            <div class="info">
              <label>Email</label>
              <p>$email</p>
            </div>
            <div class="info">
              <label style="font-size: 16px;">Report Status</label>
              <p style="color: $statusColor; font-size: 24px" >$status</p>
            </div>
          </div>
        </div>
      HTML;
      echo $htmlContent;
    }
  }

  ?>
  <button id="backBtn" onclick=window.location.href='../../welcomePage.php'>Back</button>
</body>

</html>