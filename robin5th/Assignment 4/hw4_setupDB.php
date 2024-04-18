<!-- I certify that this submission is my own original work -->
<?php
// An heredoc string to display my name and the assignment
$fieldset = <<<END
<fieldset>
<legend>Assignment 4</legend>
<label>Obaedur Rahman</label>
</fieldset>
END;
echo $fieldset;

// Importing the login file
require_once 'hw4_login.php';

// If there was any error we end the program else we echo Connection Successful...
$connection = new mysqli($host, $user, $pass, $data);
if ($connection->connect_error) {
  die($connection->connect_error);
} else {
  echo "Connection Successful..." . "<br>";
}

// This function creates the student table take the connection as the parameter
function createStudentTable($connection)
{
  // This query creates the student table and sets the fields with appropriate data type. With SID as the primary key
  $query = "CREATE TABLE IF NOT EXISTS student (
  sid SMALLINT UNSIGNED NOT NULL,
  name VARCHAR(32) NOT NULL,
  email VARCHAR(32) NOT NULL,
  phone VARCHAR(32) NOT NULL, 
  start_year YEAR NOT NULL,
  gpa DECIMAL(3,2),
  PRIMARY KEY(sid)
)";

  // Execute the query and save the result in the $result variable
  $result = $connection->query($query);


  if ($result) {
    // if $result is true,
    echo "Table 'student' created successfully" . "<br>";
  } else {
    die("Error creating table " . mysqli_error($connection));
  }
}
createStudentTable($connection);

// This function inserts student information in the student table
function insertStudentInfo($connection)
{
  // Gathering all the data in the assocciative array so later i can loop through them and enter them in the table one by one
  $students = [
    ['sid' => 12202, 'name' => 'Zetty Liberman', 'email' => 'liebez@far.edu', 'phone' => '631-348-4873', 'start_year' => 2021, 'gpa' => 2.80],
    ['sid' => 15483, 'name' => 'Jack Allison', 'email' => 'allisj@far.edu', 'phone' => '234-837-9872', 'start_year' => 2021, 'gpa' => 3.75],
    ['sid' => 27372, 'name' => 'Kyle Menchin', 'email' => 'menchk@far.edu', 'phone' => '929-384-1927', 'start_year' => 2022, 'gpa' => 3.10],
    ['sid' => 42010, 'name' => 'Alice Brown', 'email' => 'browna@far.edu', 'phone' => '212-123-4567', 'start_year' => 2023, 'gpa' => 3.50],
  ];

  // Redying insert query to be used with the prepare method.
  $query = "INSERT INTO student (sid, name, email, phone, start_year, gpa) VALUES (?, ?, ?, ?, ?, ?)";

  // Passing our query to the prepare method and saving the mysqli_stmt object in the $preparedStmt variable
  $preparedStmt = $connection->prepare($query);

  // Now for each student data we first bind the data with the $preparedStmt variable
  foreach ($students as $st) {
    $preparedStmt->bind_param("issssd", $st['sid'], $st['name'], $st['email'], $st['phone'], $st['start_year'], $st['gpa']);

    // Then we execute the statement. if there was an error we provide an error message with the corresponding sid
    if (!$preparedStmt->execute()) {
      echo "Error inserting data for SID " . htmlspecialchars($st['sid']) . "<br>";
    }
  }

  // Finally we provide a messgae
  echo "Table 'student' populated with initial data successfully. " . "<br>";
  // close the statement
  $preparedStmt->close();
}
insertStudentInfo($connection);


// The createUserTable function creates the user table
function createUserTable($connection)
{
  // Preparing the query
  $query = "CREATE TABLE IF NOT EXISTS user (
    username VARCHAR(32) NOT NULL,
    email VARCHAR(32) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(username)
  )";

  // Then executing the query
  $result = $connection->query($query);
  if ($result) {
    echo "Table 'user' created successfully." . "<br>";
  } else {
    die("Error creating table " . mysqli_error($connection));
  }
}
createUserTable($connection);
