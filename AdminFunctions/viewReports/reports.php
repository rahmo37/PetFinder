<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./reports.css">
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


  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
      case 'lost':
        lostreport($conn);
        break;
      case 'found':
        foundreport($conn);
        break;
      default:
        echo "No function specified";
        break;
    }
  }

  function lostreport($conn)
  {

    echo '<p id="title">Lost Reports</p>';
    // Building the query for viewing lost reports
    $query = "SELECT 
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
  WHERE l.reportstatus = 'Accepted'
  ORDER BY l.ReportDate DESC;";

    $result = $conn->query($query);

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


  function foundreport($conn)
  {

    echo '<p id="title">Found Reports</p>';
    // Building the query for viewing lost reports
    $query = "SELECT 
    fr.Breed as breed, 
    fr.Color AS color,
    fr.Species AS species,
    fr.FoundLocation AS foundLocation,   
    fr.FoundDate AS foundDate, 
    fr.ReportDate AS reportDate,
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
  WHERE fr.ReportStatus = 'Accepted'
  ORDER BY fr.ReportDate DESC;";


    $result = $conn->query($query);

    for ($i = 0; $i < $result->num_rows; $i++) {
      $row = $result->fetch_assoc();
      $phoneNumber = htmlspecialchars($row['contactNumber']);
      $email = htmlspecialchars($row['email']);
      $breed = htmlspecialchars($row['breed']);
      $species = htmlspecialchars($row['species']);
      $color = htmlspecialchars($row['color']);
      $rDate = htmlspecialchars($row['reportDate']);
      $founderName = htmlspecialchars($row['founderName']);
      $foundLocation = htmlspecialchars($row['foundLocation']);
      $foundDate = htmlspecialchars($row['foundDate']);
      $PhotoURL = !htmlspecialchars($row['PhotoURL']) ? "NoImage.jpg" : htmlspecialchars($row['PhotoURL']);

      $htmlContent = <<<HTML
        <div class="container">
          <div class="img-name-container">
            <img src="../../Images/$PhotoURL" alt="" id="petImage" />
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
          <hr> <!-- Line between the sections -->
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
          </div>
        </div>
      HTML;
      echo $htmlContent;
    }
  }

  ?>
  <button id="backBtn" onclick=window.location.href='./selectReport.html'>Back</button>
</body>

</html>