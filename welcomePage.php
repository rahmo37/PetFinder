<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome Menu</title>
  <style>
    body,
    html {
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: #e2e1dc;
      background-image: url("./images/Welcome2.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      transition: all 0.3s ease-in-out;
    }

    .menu-container {
      min-width: 60%;
      padding: 60px;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(8px);
      border-radius: 15px;
      border: solid 1px #fff;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
      display: flex;
      justify-content: space-around;
      /* This will space out the buttons evenly */
      flex-wrap: wrap;
      /* Allows buttons to wrap to the next line on small screens */
    }

    .menu-button {
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
      transition: all 0.4s ease-in-out;
    }

    @keyframes beatingGlow {

      0%,
      100% {
        box-shadow: 0px 0 0px #c1fc00;
      }

      50% {
        box-shadow: 0 0 20px #c1fc00;
      }
    }

    .menu-button:hover {
      background-color: rgb(255, 255, 255);
      color: #767676;
      animation: beatingGlow 2s infinite ease-in-out;
      text-shadow: none;
    }

    .menu-button:active {
      transform: scale(95%);
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .container .messageContainer h3 {
      font-size: 60px;
      color: #fff;
    }

    #welcomeTitle {
      color: #fff;
      text-shadow: 2px 2px 5px #000000;
    }

    #username {
      position: fixed;
      font-size: 25px;
      color: #fff;
      left: 40px;
      text-shadow: 2px 2px 4px #000000;
      top: 25px;
    }

    .logOut {
      position: absolute;
      right: 40px;
      font-size: 25px;
      text-shadow: 2px 2px 4px #000000;
      top: 25px;
    }

    @media (max-width: 600px) {
      .menu-container {
        flex-direction: column;
      }

      .menu-button {
        width: 80%;
        margin: 10px auto;
      }
    }
  </style>
</head>

<body>
  <?php
  session_start();
  // var_dump($_SESSION);
  // Check if isAdmin is true in the session
  if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
    $username = htmlspecialchars($_SESSION['username']);
    echo '
          <div class="container">
            <div class="messageContainer">
              <h3 id="welcomeTitle">Welcome to PetFinder</h3>
              <p id="username">' . $username . ' (Admin)</p>
            </div>
            <div class="menu-container">
              <button class="menu-button" onclick="window.location.href=\'./AdminFunctions/viewReports/selectReport.html\'">
                View Reports
              </button>
      
              <button class="menu-button" onclick="window.location.href=\'./AdminFunctions/searchReports/selectReport.html\'">
                Search Reports
              </button>
      
              <button class="menu-button" onclick="window.location.href=\'./AdminFunctions/approveReports/selectReport.html\'">
                Approve Reports
              </button>
      
              <button class="menu-button" onclick="window.location.href=\'./AdminFunctions/deleteReports/selectReport.html\'">
                Delete Reports
              </button>
            </div>
            <a class="menu-button logOut" href="./UserLoginAndRegistration/logout.php?logout=1" style="text-decoration: none;">
            Logout
          </a>
          </div>';
  } else if (isset($_SESSION['reportType']) && $_SESSION['reportType'] === "lost") {
    $username = htmlspecialchars($_SESSION['username']);
    echo '
    <div class="container">
      <div class="messageContainer">
      <h3 id="welcomeTitle">Welcome to PetFinder</h3>
      <p id="username">' . $username . ' (Lost Report Profile)</p>
      </div>
      <div class="menu-container">
        <button class="menu-button" onclick="window.location.href=\'./LostUserFunctions/FileALostreport/fileLostReport.html\'">
        File A Lost Report
        </button>

        <button class="menu-button" onclick="window.location.href=\'./LostUserFunctions/ViewReports/userLostReports.php\'">
          View Your Reports
        </button>

        <button class="menu-button" onclick="window.location.href=\'./LostUserFunctions/DeleteYourReports/deleteUserLostReports.php\'">
          Delete Your Reports
        </button>

        <button class="menu-button" onclick="window.location.href=\'./LostUserFunctions/ViewCurrentFoundReports/viewCurrentFoundReports.php\'">
        View Found Reports
        </button>

        <button class="menu-button" onclick="window.location.href=\'./LostUserFunctions/SearchFoundReports/searchFoundReports.php\'">
        Search Found Reports
      </button>
      </div>
      <a class="menu-button logOut" href="./UserLoginAndRegistration/logout.php?logout=1" style="text-decoration: none;">
      Logout
    </a>
    </div>';
  } else if (isset($_SESSION['reportType']) && $_SESSION['reportType'] === "found") {
    $username = htmlspecialchars($_SESSION['username']);
    echo '
    <div class="container">
      <div class="messageContainer">
      <h3 id="welcomeTitle">Welcome to PetFinder</h3>
      <p id="username">' . $username . ' (Found Report Profile)</p>
      </div>
      <div class="menu-container">
        <button class="menu-button" onclick="window.location.href=\'./AdminFunctions/viewReports/selectReport.html\'">
        File A Found Report
        </button>

        <button class="menu-button" onclick="window.location.href=\'./FoundUserFunctions/viewReports/userFoundReports.php\'">
          View You Reports
        </button>

        <button class="menu-button" onclick="window.location.href=\'./FoundUserFunctions/DeleteYourReports/deleteUserFoundReports.php\'">
          Delete Your Reports
        </button>

        <button class="menu-button" onclick="window.location.href=\'./FoundUserFunctions/ViewCurrentLostReports/viewCurrentLostReports.php\'">
        View Lost Reports
        </button>

        <button class="menu-button" onclick="window.location.href=\'./FoundUserFunctions/SearchLostReports/searchLostReports.php\'">
        Search Lost Reports
      </button>
      </div>
      <a class="menu-button logOut" href="./UserLoginAndRegistration/logout.php?logout=1" style="text-decoration: none;">
      Logout
    </a>
    </div>';
  } else {
    echo '
    <div class="container">
    <h1 id="welcomeTitle">Page Not Found.</h1>
    <h2 style="color: white; text-shadow: 2px 2px 4px #000000;">Please click the below link to go to the login page</h2>
    <a href="./UserLoginAndRegistration/login.html" class="menu-button">Login Page</a>
    </div>';
  }
  ?>
</body>

</html>