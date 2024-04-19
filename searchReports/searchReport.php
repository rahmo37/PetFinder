<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<style>
  body {
    height: 100%;
    margin: 0;
    font-family: Arial, sans-serif;
    background-image: linear-gradient(to right,
        rgb(182, 244, 146),
        rgb(51, 139, 147));
    display: flex;
    justify-content: center;
    align-items: center;
  }


  form {
    background-image: linear-gradient(to right,
        rgb(151, 203, 121),
        rgb(51, 139, 147));
    height: 60vh;
    padding: 20px;
    margin-top: 100px;
    border-radius: 8px;
    width: calc(100% - 100px);
    /* Adjust width based on padding or parent width */
    max-width: 400px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
  }

  label {
    font-size: 16px;
    /* Larger font size for better readability */
    color: #333;
    /* Dark gray color for text for better visibility */
    margin-bottom: 10px;
    /* Adds space below the label */
    display: block;
    /* Makes the label take a full width, organizing the form */
  }

  select,
  input[type="text"] {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: none;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    color: #696969;
  }

  select,
  input[type="text"]:focus {
    border: none;
    outline: none;
  }



  button {
    background-color: transparent;
    color: white;
    padding: 20px 20px;
    margin: 10px;
    border: 2px solid white;
    border-radius: 20px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    outline: none;
    transition: all 0.3s ease-in-out;
  }

  #backBtn {
    position: fixed;
    right: 125px;
    top: 10px;
  }

  @keyframes beatingGlow {

    0%,
    100% {
      box-shadow: 0 0 0 0 #c1fc00;
    }

    50% {
      box-shadow: 0 0 20px #c1fc00;
    }
  }

  button:hover {
    background-color: rgb(255, 255, 255);
    color: #767676;
    animation: beatingGlow 2s infinite ease-in-out;
  }
</style>

<body>
  <?php
  if (isset($_GET['action'])) {
    switch ($_GET['action']) {
      case 'lost':
        loadLostReportHtml();
        break;
      case 'found':
        loadFoundReportHtml();
        break;
      default:
        echo "No function specified";
        break;
    }
  }


  function loadLostReportHtml()
  {
    $savedField = isset($_GET['savedField']) ? trim(htmlspecialchars($_GET['savedField'])) : '';
    $validFields = ['petName', 'ownerName', 'species', 'breed', 'color', 'lastSeenLocation', 'lastSeenDate', 'reportStatus', 'reportDate'];

    // Build options for the dropdown
    $options = '';
    foreach ($validFields as $field) {
      $selected = $savedField === $field ? 'selected' : '';
      $options .= "<option value=\"$field\" $selected>" . ucfirst($field) . "</option>";
    }

    // Output the form
    echo "<form action='searchAndDisplay.php' method='POST'>
            <label for='field-select'>Choose a field from Lost Report table:</label>
            <select id='field-select' name='fieldName'>
                $options
            </select>

            <label for='field-value'>Enter information:</label>
            <input type='text' id='field-value' name='info'/>

            <input type='hidden' name='lost' value='lost'>

            <button type='submit'>Search</button>
          </form>";
  }



  function loadFoundReportHtml()
  {
    $savedField = isset($_GET['savedField']) ? trim(htmlspecialchars($_GET['savedField'])) : '';

    // Define valid fields
    $validFields = ['finderName', 'breed', 'species', 'color', 'foundLocation', 'foundDate', 'reportStatus', 'reportDate'];

    // Build options for the dropdown
    $options = '';
    foreach ($validFields as $field) {
      $selected = $savedField === $field ? 'selected' : '';
      $options .= "<option value=\"$field\" $selected>" . ucfirst($field) . "</option>";
    }

    // Output the form
    echo "<form action='searchAndDisplay.php' method='POST'>
            <label for='field-select'>Choose a field from Finder Report table:</label>
            <select id='field-select' name='fieldName'>
                $options
            </select>

            <label for='field-value'>Enter information:</label>
            <input type='text' id='field-value' name='info'/>

            <input type='hidden' name='found' value='found'>

            <button type='submit'>Search</button>
          </form>";
  }
  ?>

  <button id="backBtn" onclick=window.location.href='./selectReport.html'>Back</button>

  <body>

</html>