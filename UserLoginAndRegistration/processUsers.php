<?php
// I certify that this submission is my own original work

// Verifies the uniqueness of a username and email to ensure that each user is distinct, Performs Server-side validation, after validation adds the user to the database, also checks username and password when the user is trying to login and starts a session upon successful login

require_once '../login.php';
// Establishing connection with database
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
  die("Fatal Error");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  //! ===== cheking unique Username and Email =====
  if (isset($_POST["flag"]) && $_POST["flag"] === "checkUsernameAndEmail") { // If the flag sent is checkUsernameAndEmail
    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);

    // Starting to check...
    // UserName
    $query = "SELECT 1 FROM users WHERE username = ?"; // This query will return 1 for each row that matches the username
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $userExists = $result->num_rows;

    // Email

    $query = "SELECT 1 FROM users WHERE email = ?"; // This query will return 1 for each row that matches the Email
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $emailExist = $result->num_rows;
    // If the sum of the number is greater than 0 then we send the corresponding field as a string, i will handle the sting in the registration page with javascript
    if (($userExists + $emailExist) > 0) {
      // Using ternerry operator to check which attribute has a value of 1, or if both.
      exit(($userExists > 0 ? "username" : "") . " " . ($emailExist > 0 ? "email" : ""));
    } else {
      // Else i send empty string indicating to client-side that there are no errors
      exit("");
    }
  }
  //! ===== Adding the user after server side validation =====
  else if (isset($_POST["flag"]) && $_POST["flag"] === "addUser") { // If the flag send is add user

    $username = htmlspecialchars($_POST["username"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Server Side Validation
    // Validating inputs before insertion
    $fail = "";
    $fail .= validate_username($username);
    $fail .= validate_password($password);
    $fail .= validate_email($email);

    if ($fail === "") {
      $password = password_hash(htmlspecialchars($_POST["password"]), PASSWORD_DEFAULT); // Password hashing
      $isAdmin = 0;
      // echo $username . $email . $password . $confPass;
      $query = "INSERT INTO users (username, password, email, isAdmin)
    VALUES (?,?,?,?);";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssi", $username, $password, $email, $isAdmin);
      $stmt->execute();
      $result = $stmt->affected_rows;
      if ($result > 0) {
        exit("User account created succesfully");
      } else {
        exit("User registration failed: " . $conn->error);
      }
    } else {
      exit($fail);
    }
  }
  //! ===== cheking if username and assocciated password matches =====
  else if (isset($_POST["flag"]) && $_POST["flag"] === "checkUserValidity") {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    $reportType = htmlspecialchars($_POST["reportType"]);

    // Starting to check...
    // UserName
    $query = "SELECT 1 FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $userExists = $result->num_rows;

    if ($userExists) {
      // Password
      $query = "SELECT Password, IsAdmin FROM users WHERE username = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc();
        $storedHash = $row['Password'];
        $isAdmin = $row['IsAdmin'] ? true : false; // Storing the status of the isAdmin 
        if (password_verify($password, $storedHash)) {
          session_start();
          session_regenerate_id(true); // Regenrating the session id when a new login occurs
          $_SESSION = array(); // Deleting all prevous data
          // Saving information in the Session
          $_SESSION['isAdmin'] = $isAdmin;
          $_SESSION['reportType'] = $reportType;
          $_SESSION['username'] = $username;
          exit("");
        } else {
          exit("password"); // If password is not a match we send the string password
        }
      }
    } else {
      exit("username");
    }
  }
}

// Helper functions for validation

function validate_username($field)
{
  if ($field == "") {
    return "No Username was entered\n";
  } else if (strlen($field) < 5) {
    return "Usernames must be at least 5 characters long\n";
  } else if (preg_match("/[^a-zA-Z0-9_-]/", $field)) {
    return "Only letters, numbers, - and _ in usernames\n";
  }
  return "";
}

function validate_password($field)
{
  if ($field == "") {
    return "No Password was entered\n";
  } else if (strlen($field) < 8) {
    return "Passwords must be at least 8 characters\n";
  } else if (!preg_match("/[a-z]/", $field) || !preg_match("/[A-Z]/", $field) || !preg_match("/[0-9]/", $field)) {
    return "Passwords require 1 of each a-z, A-Z and 0-9\n";
  }
  return "";
}

function validate_email($field)
{
  if ($field == "") {
    return "No Email was entered\n";
  } else if (!(strpos($field, ".") > 0 && strpos($field, "@") > 0) || preg_match("/[^a-zA-Z0-9.@_-]/", $field)) {
    return "The Email address is invalid\n";
  }
  return "";
}
