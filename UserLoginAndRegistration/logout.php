<?php
// I certify that this submission is my own original work

// This file manages the logout process. Checks for an actual logout request, Starts the session if not already started, clears all session variables, deletes the session cookie, and destroys the session to ensure a complete logout. After ending the session, it redirects the user to login.html.

//! ===== Checking for logout request and then calling the endSession() =====
if (isset($_GET['logout'])) { // Checking for a log out request
  endSession();
  header("Location: login.html");
  exit();
}


//! ===== End Session Variable =====
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
