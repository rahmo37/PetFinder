<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="/images/logo.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
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
      height: 50vh;
      padding: 20px;
      margin-top: 100px;
      border-radius: 8px;
      width: calc(100% - 100px);
      max-width: 400px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
      display: flex;
      justify-content: center;
      align-items: flex-start;
      flex-direction: column;
    }

    label {
      font-size: 20px;
      color: #333;
      margin-bottom: 10px;
      display: block;
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
      0%, 100% { box-shadow: 0 0 0 0 #c1fc00; }
      50% { box-shadow: 0 0 20px #c1fc00; }
    }

    button:hover {
      background-color: rgb(255, 255, 255);
      color: #767676;
      animation: beatingGlow 2s infinite ease-in-out;
    }
  </style>
</head>

<body>
<?php

session_start();
if (isset($_SESSION['username'])) {
  loadFoundReportHtml();
} else {
  echo '
  <style>
  #backBtn {
    display: none;
  }
  </style>
  <div>
  <h1 id="welcomeTitle">Page Not Found.</h1>
  <h2 style="color: white; text-shadow: 2px 2px 4px #000000;">Please click the below link to go to the login page</h2>
  <a href="../../UserLoginAndRegistration/login.html" class="menu-button">Login Page</a>
  </div>';
}

function loadFoundReportHtml()
{
  $savedField = isset($_GET['savedField']) ? trim(htmlspecialchars($_GET['savedField'])) : '';
  // Define valid fields
  $validFields = ['species', 'breed',  'color', 'foundLocation', 'foundDate', 'reportDate'];
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

<button id="backBtn" onclick="window.location.href='../../welcomePage.php'">Back</button>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('field-select');
    const input = document.getElementById('field-value');
    function updatePlaceholder() {
      if (!select || !input) return;
      if (select.value === 'foundDate' || select.value === 'reportDate') {
        input.placeholder = 'yyyy-mm-dd';
      } else {
        input.placeholder = '';
      }
    }
    if (select && input) {
      select.addEventListener('change', updatePlaceholder);
      updatePlaceholder();
    }
  });
</script>

</body>
</html>
