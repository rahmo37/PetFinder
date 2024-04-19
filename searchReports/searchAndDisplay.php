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
  require_once '../login.php';
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
      $info =  trim(htmlspecialchars($_POST["info"]));
      if ($fieldName === "ownerName") {
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
        l.ReportStatus AS rStatus,
        l.LastSeenLocation AS lastSeenLocation,
        l.LastSeenDate AS lastSeenDate, 
        l.PhotoURL
      FROM 
        lostreport l 
      JOIN 
        owners o ON l.ownerid = o.OwnerID
      WHERE 
        o.name = ?
      ORDER BY 
        l.ReportDate DESC;
      ";
      } else {
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
        l.ReportStatus AS rStatus,
        l.LastSeenLocation AS lastSeenLocation,
        l.LastSeenDate AS lastSeenDate, 
        l.PhotoURL
      FROM 
      lostreport l 
      JOIN 
        owners o 
      ON 
        l.OwnerID = o.OwnerID 
      WHERE l.$fieldName = ?
      ORDER BY l.ReportDate DESC;
      ";
      }

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
          $status = htmlspecialchars($row['rStatus']);
          $PhotoURL = !htmlspecialchars($row['PhotoURL']) ? "NoImage.jpg" : htmlspecialchars($row['PhotoURL']);
          $statusColor = $status === "Accepted" ? "#37ea18" : "#ff7f50";

          $htmlContent = <<<HTML
            <div class="container">
              <div class="img-name-container">
                <img src="../Images/$PhotoURL" alt="" id="petImage" />
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
      echo '<form action="searchReport.php" method="get">
        <input type="hidden" value="' . $fieldName . '" name="savedField">
        <button id="backBtn" type="submit" value="lost" name="action">Back</button>
        </form>';
    } else {
      $fieldName = trim(htmlspecialchars($_POST["fieldName"]));
      $info = trim(htmlspecialchars($_POST["info"]));
      if ($fieldName === "finderName") {
        $query = "
        SELECT 
        fr.Breed as breed, 
        fr.Color AS color,
        fr.Species AS species,
        fr.FoundLocation AS foundLocation,   
        fr.FoundDate AS foundDate, 
        fr.ReportDate AS reportDate,
        fr.ReportStatus AS rStatus,
        f.Name AS founderName,
        f.ContactNumber AS contactNumber,
        f.Email AS email,
        fr.PhotoURL
      FROM 
      finderreports fr 
      JOIN 
        finder f ON fr.FinderId = f.FinderId 
      WHERE f.name = ?
      ORDER BY fr.ReportDate DESC;
      ";
      } else {
        $query = "
        SELECT 
        fr.Breed as breed, 
        fr.Color AS color,
        fr.Species AS species,
        fr.FoundLocation AS foundLocation,   
        fr.FoundDate AS foundDate, 
        fr.ReportDate AS reportDate,
        fr.ReportStatus AS rStatus,
        f.Name AS founderName,
        f.ContactNumber AS contactNumber,
        f.Email AS email,
        fr.PhotoURL
      FROM 
      finderreports fr 
      JOIN 
        finder f 
      ON 
        fr.FinderId = f.FinderId 
      WHERE fr.$fieldName = ?
      ORDER BY fr.ReportDate DESC;
      ";
      }

      $stmt = $conn->prepare($query);
      $stmt->bind_param('s', $info);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows === 0) {
        echo '<p id="title">No result found!</p>';
      } else {
        echo '<p id="title">Found Report(s)</p>';
        for ($i = 0; $i < $result->num_rows; $i++) {
          $row = $result->fetch_assoc();
          $phoneNumber = htmlspecialchars($row['contactNumber']);
          $email = htmlspecialchars($row['email']);
          $breed = htmlspecialchars($row['breed']);
          $species = htmlspecialchars($row['species']);
          $color = htmlspecialchars($row['color']);
          $rDate = htmlspecialchars($row['reportDate']);
          $status = htmlspecialchars($row['rStatus']);
          $founderName = htmlspecialchars($row['founderName']);
          $foundLocation = htmlspecialchars($row['foundLocation']);
          $foundDate = htmlspecialchars($row['foundDate']);
          $PhotoURL = !htmlspecialchars($row['PhotoURL']) ? "NoImage.jpg" : htmlspecialchars($row['PhotoURL']);
          $statusColor = $status === "Accepted" ? "#37ea18" : "#ff7f50";

          $htmlContent = <<<HTML
            <div class="container">
                <div class="img-name-container">
                  <img src="../Images/$PhotoURL" alt="" id="petImage" />
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
                  <label>Found Location</label>
                  <p>$foundLocation</p>
                </div>
                <div class="info">
                  <label>Found Date</label>
                  <p>$foundDate</p>
                </div>
                <div class="info">
                  <label>Report Date</label>
                  <p>$rDate</p>
                </div>
                </div>
                <hr>
                <div class="pet-info-container">
                <div class="info">
                  <label>Founder</label>
                  <p>$founderName</p>
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
      echo '<form action="searchReport.php" method="get">
      <input type="hidden" value="' . $fieldName . '" name="savedField">
        <button id="backBtn" type="submit" value="found" name="action">Back</button>
        </form>';
    }
  }


  ?>
</body>
</body>

</html>