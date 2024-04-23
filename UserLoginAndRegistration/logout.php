<?php

if (isset($_GET['logout'])) {
  endSession();
  header("Location: login.html");
  exit();
}


function endSession()
{
  // Making sure the session is started
  if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }

  // Unseting all of the session variables from other logins
  $_SESSION = array();

  // Deleting the session cookie
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params['path'],
      $params['domain'],
      $params['secure'],
      $params['httponly']
    );
  }

  // Destroy the session
  session_destroy();
}

endSession();

header("Location: login.html");
exit();
