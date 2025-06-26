<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./approveReports.css">
  <link rel="icon" href="/images/logo.png" type="image/png">
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

  session_start();
  if (isset($_SESSION['username'])) {
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
    die();
  }

  // !-----------------

  function lostreport($conn)
  {

    echo '<p id="title">Pending Lost Reports</p>';
    // Building the query for viewing lost reports
    $query = "SELECT 
    l.ReportID as reportID,
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
  WHERE l.Reportstatus = 'Pending';";


    $result = $conn->query($query);

    for ($i = 0; $i < $result->num_rows; $i++) {
      $row = $result->fetch_assoc();
      $reportID = htmlspecialchars($row['reportID']);
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
      $messageID = $i;

      $htmlContent = <<<HTML
      <div class="container" data-report-id="{$reportID}">
        <div class="img-name-container">
          <img src="../../images/{$PhotoURL}" alt="" id="petImage" />
          <p class="message" style="opacity: 0" id="message-{$messageID}"></p>
          <div class="name-container">
            <p id="petName">{$petName}</p>
          </div>
          <div class="reportStatusBtn">
          <button id="accept" onclick="updateReportStatus(this,'{$reportID}', 'Accepted', 'lost', '{$messageID}');">&#x2714;</button>
          <button id="reject" onclick="updateReportStatus(this,'{$reportID}', 'Rejected', 'lost','{$messageID}');">&#x2715;</button>
          </div>
        </div>
        <div class="pet-info-container">
          <div class="info">
            <label>Breed</label>
            <p>{$breed}</p>
          </div>
          <div class="info">
            <label>Color</label>
            <p>{$color}</p>
          </div>
          <div class="info">
            <label>Species</label>
            <p>{$species}</p>
          </div>
          <div class="info">
            <label>Last Seen Location</label>
            <p>{$lastSeenLocation}</p>
          </div>
          <div class="info">
            <label>Last Seen Date</label>
            <p>{$lastSeenDate}</p>
          </div>
          <div class="info">
            <label>Report Date</label>
            <p>{$rDate}</p>
          </div>
        </div>
        <hr>
        <div class="pet-info-container">
          <div class="info">
            <label>Owner</label>
            <p>{$ownerName}</p>
          </div>
          <div class="info">
            <label>Phone</label>
            <p>{$phoneNumber}</p>
          </div>
          <div class="info">
            <label>Email</label>
            <p>{$email}</p>
          </div>
        </div>
      </div>
HTML;
      echo $htmlContent;
    }
  }

  // !-----------------

  function foundreport($conn)
  {

    echo '<p id="title">Pending Found Reports</p>';
    // Building the query for viewing lost reports
    $query = "SELECT 
    fr.ReportID as reportID,
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
  WHERE fr.ReportStatus = 'Pending';";


    $result = $conn->query($query);

    for ($i = 0; $i < $result->num_rows; $i++) {
      $row = $result->fetch_assoc();
      $reportID = htmlspecialchars($row['reportID']);
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
      $messageID = $i;

      $htmlContent = <<<HTML
        <div class="container" data-report-id="{$reportID}" data-message-id="{$messageID}">
          <div class="img-name-container">
            <img src="../../images/$PhotoURL" alt="" id="petImage" />
            <p class="message" style="opacity: 0" id="message-{$messageID}"></p>
            <div class="reportStatusBtn">
            <button id="accept" onclick="updateReportStatus(this,'{$reportID}', 'Accepted', 'found', '{$messageID}');">&#x2714;</button>
          <button id="reject" onclick="updateReportStatus(this,'{$reportID}', 'Rejected', 'found','{$messageID}');">&#x2715;</button>
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
<script>
  function updateReportStatus(buttonElement, reportId, status, reportType, messageId) {
    console.log(messageId);
    if (confirm(status.substring(0, status.length - 2) + " this request?")) {
      fetch('processReport.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `reportId=${reportId}&status=${status}&reportType=${reportType}`
        })
        .then(response => response.text())
        .then(data => {
          // if deletion is successful
          if (data.includes("Successful")) {
            let message = document.getElementById("message-" + messageId);
            let color = status === "Accepted" ? "#37ea18" : "#ff2800";
            message.style.color = color;
            message.innerHTML = status;
            message.style.opacity = "1";



            // The buttonElement.closest('.container') is a JavaScript method used to find the nearest ancestor of the buttonElement that matches the specified selector—in this case, .container.
            let reportContainer = buttonElement.closest('.container');
            reportContainer.style.opacity = '0';
            setTimeout(() => {
              reportContainer.remove();
            }, 1500);
          } else {
            console.log(data);
            alert("Opeartion Failed!");
          }
        })
        .catch(error => {
          alert('Error:', error);
        });
    }
  }
</script>

</html>