<?php
require_once '../login.php';

// Establishing connection with database
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
  die("Fatal Error");
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['reportId']) && $_POST['status'] === 'Accepted' && $_POST['reportType'] === 'lost') {
    $reportId = $conn->real_escape_string($_POST['reportId']);
    $newStatus = "Accepted";
    $stmt = $conn->prepare("UPDATE lostreport SET ReportStatus = ? WHERE ReportId = ?");
    $stmt->bind_param("si", $newStatus, $reportId);
    if ($stmt->execute()) {
      echo "Successful";
    } else {
      echo "Failed" . $stmt->error;
    }
  } else if (isset($_POST['reportId']) && $_POST['status'] === 'Rejected' && $_POST['reportType'] === 'lost') {

    // Here i am going use a transaction, because my goal is not to delete the report if rejected but also the assocciated owner as well, but only if the owner does not have any other reports. This is a sophisticated opetation, and if i encounter exception in any point of my opration i would like to rollbcak.

    // Begin transaction
    $conn->begin_transaction();
    try {
      // Fetching the ownerID before deleting the report, if a successful ID was fetched, true is returned and i save the result in the $ownerFetched variable
      $reportId = $conn->real_escape_string($_POST['reportId']);
      $ownerId = 0;
      $count = 0;
      $stmt = $conn->prepare("SELECT ownerId FROM lostreport WHERE reportId = ?");
      $stmt->bind_param("i", $reportId);
      $stmt->execute();
      $stmt->bind_result($ownerId); // Binding the ownerId returned to the ownerId variable
      $ownerFetched = $stmt->fetch(); // Either true or false
      $stmt->close();

      if ($ownerFetched) {
        // After reciving the ownerID now we can delete the report
        $stmt = $conn->prepare("DELETE FROM lostreport WHERE reportId = ?");
        $stmt->bind_param("i", $reportId);
        $stmt->execute();
        $stmt->close();

        // After deleteing the report, now we decide whether we are going to delete the owner or if they have more entries in the table.
        $stmt = $conn->prepare("SELECT COUNT(*) FROM lostreport WHERE ownerID = ?");
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $stmt->bind_result($count); // binding the numbner of report in the count variable
        $stmt->fetch();
        $stmt->close();
      }

      // Now if the owner does not have any other report then we delete him/her
      if ($count == 0 && $ownerFetched) {
        $stmt = $conn->prepare("DELETE FROM owners WHERE ownerID = ?");
        $stmt->bind_param("i", $ownerId);
        $stmt->execute();
        $stmt->close();
      }
      $conn->commit();
      echo "Successful";
    } catch (Exception $e) {
      // Rollback transaction on error
      $conn->rollback();
      echo "Failed";
    }
  }
  if (isset($_POST['reportId']) && $_POST['status'] === 'Accepted' && $_POST['reportType'] === 'found') {
    $reportId = $conn->real_escape_string($_POST['reportId']);
    $newStatus = "Accepted";
    $stmt = $conn->prepare("UPDATE finderreports SET ReportStatus = ? WHERE ReportId = ?");
    $stmt->bind_param("si", $newStatus, $reportId);
    if ($stmt->execute()) {
      echo "Successful";
    } else {
      echo "Failed" . $stmt->error;
    }
  }
}
