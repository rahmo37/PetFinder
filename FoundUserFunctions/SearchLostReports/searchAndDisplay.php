<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./searchReport.css">
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
  // var_dump($_POST); 
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['lost'])) {
      $fieldName = trim(htmlspecialchars($_POST["fieldName"]));
      $info = trim(htmlspecialchars($_POST["info"]));
      $query = "
        SELECT 
          l.PetName as petName, 
          o.Name AS ownerName,
          o.ContactNumber AS phoneNumber,
          o.Email AS email,   
          l.Breed AS breed, 
          l.Species AS species,
          l.Color AS color,
          l.ReportDate AS reportDate, 
          l.LastSeenLocation AS lastSeenLocation,
          l.LastSeenDate AS lastSeenDate, 
          l.PhotoURL
        FROM 
        lostreport l 
        JOIN 
          owners o 
        ON 
          l.OwnerID = o.OwnerID 
        WHERE l.$fieldName = ? AND l.ReportStatus = 'Accepted'
        ORDER BY l.ReportDate DESC;
      ";


      $stmt = $conn->prepare($query);
      $stmt->bind_param('s', $info);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 0) {
        echo '<p id="title">No result found!</p>';
      } else {
        echo '<p id="title">Lost Report(s)</p>';
        for ($i = 0; $i < $result->num_rows; $i++) {
          $row = $result->fetch_assoc();
          $petName = htmlspecialchars($row['petName']);
          $ownerName = htmlspecialchars($row['ownerName']);
          $phoneNumber = htmlspecialchars($row['phoneNumber']);
          $email = htmlspecialchars($row['email']);
          $breed = htmlspecialchars($row['breed']);
          $species = htmlspecialchars($row['species']);
          $color = htmlspecialchars($row['color']);
          $rDate = htmlspecialchars($row['reportDate']);
          $lastSeenLocation = htmlspecialchars($row['lastSeenLocation']);
          $lastSeenDate = htmlspecialchars($row['lastSeenDate']);
          $PhotoURL = !htmlspecialchars($row['PhotoURL']) ? "NoImage.jpg" : htmlspecialchars($row['PhotoURL']);

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
              </div>
            </div>
          HTML;
          echo $htmlContent;
        }
      }
      echo '<form action="searchLostReports.php" method="get">
      <input type="hidden" value="' . $fieldName . '" name="savedField">
        <button id="backBtn" type="submit" value="found" name="action">Back</button>
        </form>';
    }
  } else {
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
  }
  ?>
</body>
</body>

</html>