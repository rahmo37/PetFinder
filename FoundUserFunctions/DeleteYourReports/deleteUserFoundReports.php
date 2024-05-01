<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./deleteUserFoundReports.css">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet" />
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


  // !-----------------

  deleteYourReports($conn);

  function deleteYourReports($conn)
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
      $username = $_SESSION['username'];
    }

    echo '<p id="title">Your Current Reports</p>';
    // Building the query for viewing lost reports
    $query = "SELECT 
    fr.ReportID as reportID,
    fr.Breed as breed, 
    fr.Color AS color,
    fr.Species AS species,
    fr.FoundLocation AS foundLocation,   
    fr.FoundDate AS foundDate, 
    fr.ReportDate AS rDate,
    fr.ReportStatus AS rStatus,
    f.Name AS founderName,
    f.ContactNumber AS contactNumber,
    f.Email AS email,
    fr.PhotoURL
    FROM 
      UsersReportsLink ul
    JOIN 
      finderreports fr ON ul.FinderReportID = fr.ReportID
    JOIN 
      Finder f ON fr.FinderID = f.FinderID
    WHERE 
        ul.UserName = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();

    $result = $stmt->get_result();

    for ($i = 0; $i < $result->num_rows; $i++) {
      $row = $result->fetch_assoc();
      $reportID = htmlspecialchars($row['reportID']);
      $phoneNumber = htmlspecialchars($row['contactNumber']);
      $email = htmlspecialchars($row['email']);
      $breed = htmlspecialchars($row['breed']);
      $species = htmlspecialchars($row['species']);
      $color = htmlspecialchars($row['color']);
      $rDate = htmlspecialchars($row['rDate']);
      $status = htmlspecialchars($row['rStatus']);
      $founderName = htmlspecialchars($row['founderName']);
      $foundLocation = htmlspecialchars($row['foundLocation']);
      $foundDate = htmlspecialchars($row['foundDate']);
      $PhotoURL = !htmlspecialchars($row['PhotoURL']) ? "NoImage.jpg" : htmlspecialchars($row['PhotoURL']);

      $statusColor = $status === "Accepted" ? "#37ea18" : "#ff7f50";
      $messageID = $i;

      $htmlContent = <<<HTML
        <div class="container" data-report-id="{$reportID}" data-message-id="{$messageID}">
          <div class="img-name-container">
            <img src="../../Images/$PhotoURL" alt="" id="petImage" />
            <p class="message" style="opacity: 0" id="message-{$messageID}"></p>
            <div class="deleteStatusBtn">
            <button id="delete" onclick="updateReportStatus(this,'{$reportID}', 'found','{$messageID}');"><i class="ri-delete-bin-2-line" style="font-size:24px;color:red"></i></button>
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
<script>
  function updateReportStatus(buttonElement, reportId, reportType, messageId) {
    console.log(messageId);
    if (confirm("Delete this report?")) {
      fetch('processReport.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `reportId=${reportId}&reportType=${reportType}`
        })
        .then(response => response.text())
        .then(data => {
          // if deletion is successful
          if (data.includes("Successful")) {
            let message = document.getElementById("message-" + messageId);
            let color = "#ff2800";
            message.style.color = color;
            message.innerHTML = "Deleted";
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