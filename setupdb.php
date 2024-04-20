<?php
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);

if ($conn->connect_error) {
  die("Fatal Error");
} else {
  // At first droping all the if they exists so new one can be added
  $query = "DROP TABLE IF EXISTS UsersReportsLink;";
  $query .= "DROP TABLE IF EXISTS Users;";
  $query .= "DROP TABLE IF EXISTS LostReport;";
  $query .= "DROP TABLE IF EXISTS Owners;";
  $query .= "DROP TABLE IF EXISTS FinderReports;";
  $query .= "DROP TABLE IF EXISTS Finder;";

  $result = $conn->multi_query($query);
  if (!$result) {
    die("Unable to drop Tables..." . $conn->error);
  } else {
    // This loop condition checks if there are more results to process ($conn->more_results()) and then attempts to move to the next result set with $conn->next_result(), If there's a result set, this line frees up the memory associated with that result set
    do {
      if ($result = $conn->store_result()) {
        // Freeing the memory associated with the result
        $result->free();
      }
    } while ($conn->more_results() and $conn->next_result());
  }
}

//! ------------------------------

// This function create the owner table
// Creating Owners Table first since it does not have any foreign key
function createOwnersTable($conn)
{
  // query to create the owners table
  $query = "CREATE TABLE IF NOT EXISTS Owners (
      OwnerID INT NOT NULL AUTO_INCREMENT,
      Name VARCHAR(100) NOT NULL,
      ContactNumber VARCHAR(15) NOT NULL,
      Email VARCHAR(100) NOT NULL,
      PRIMARY KEY (OwnerID),
      UNIQUE(ContactNumber),
      UNIQUE(Email),
      UNIQUE(Name, ContactNumber, Email)
    )";

  // Executing and saving the result true or false
  $result = $conn->query($query);

  // Based on the result echoing appropriate message
  if ($result) {
    echo "Owners table created or already exsists!" . "<br>";
    // Invoking the insertRecordInOwners() to insert the records
    insertRecordInOwners($conn);
  } else {
    echo "There was a problem creating the Owners table!";
  }
}

function insertRecordInOwners($conn)
{
  // The Array that contains all the Owners information
  $owners = [
    ['Name' => 'Alex Johnson', 'ContactNumber' => '123-555-0100', 'Email' => 'alexj@example.com'],
    ['Name' => 'Brenda Lee', 'ContactNumber' => '123-555-0101', 'Email' => 'brendal@example.net'],
    ['Name' => 'Charlie McDonald', 'ContactNumber' => '123-555-0102', 'Email' => 'charliem@example.org'],
    ['Name' => 'Dana Smith', 'ContactNumber' => '123-555-0103', 'Email' => 'danas@example.com'],
    ['Name' => 'Evan Roberts', 'ContactNumber' => '123-555-0104', 'Email' => 'evanr@example.net']
  ];
  // Preparing the query
  $query = 'INSERT INTO Owners(Name,ContactNumber,Email) VALUES(?,?,?)';

  // Preparing the statement with the query;
  $stmt = $conn->prepare($query);

  // For each record in the array passing the corresponding name contactnumber and the email using for each loop
  foreach ($owners as $each) {
    $stmt->bind_param("sss", $each['Name'], $each['ContactNumber'], $each['Email']);

    // After each successful binding of each record, executing the statement
    $result = $stmt->execute();
    if (!$result) {
      echo "Error inserting data for Name: " .  $each['Name'] . "<br>";
    }
  }
  echo "Owners Table Populated Succesfully!";
  $stmt->close();
}

//! ------------------------------


// This function LostReport table
function createLostReportTable($conn)
{
  // Query to create the LostReport table
  $query = "CREATE TABLE IF NOT EXISTS LostReport (
    ReportID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    OwnerID INT NOT NULL,
    PetName VARCHAR(100) NOT NULL,
    Species VARCHAR(50) NOT NULL,
    Breed VARCHAR(50) NOT NULL,
    Color VARCHAR(50) NOT NULL,
    LastSeenLocation VARCHAR(255),
    LastSeenDate Date,
    PhotoURL VARCHAR(255),
    ReportStatus VARCHAR(20),
    ReportDate DATE DEFAULT (CURDATE()),
    
    FOREIGN KEY (OwnerID) REFERENCES Owners(OwnerID),
    UNIQUE (OwnerID, PetName),
    INDEX(PetName),
    INDEX(Species),
    INDEX(Breed),
    INDEX(Color),
    INDEX(PetName,Species,Breed,Color),
    INDEX(ReportStatus)
  )";



  // Executing and saving the result in the $result 
  $result = $conn->query($query);

  // Based on the result of the query
  if ($result) {
    echo "LostReport table created or already exsists!" . "<br>";
    insertRecordInLostReportTable($conn);
  } else {
    echo "There was a problem creating the LostReport table!" . $conn->error;
  }
}

function insertRecordInLostReportTable($conn)
{
  // The Array that contains all the Lost report information
  $lostReport = [
    [
      'OwnerID' => 1,
      'PetName' => 'Jaguar',
      'Species' => 'Dog',
      'Breed' => 'Doberman Pinscher',
      'Color' => 'Black',
      'LastSeenLocation' => '123 Park Lane',
      'LastSeenDate' => '2024-03-25',
      'PhotoURL' => 'Doberman.jpg',
      'ReportStatus' => "Pending"
    ],
    [
      'OwnerID' => 1,
      'PetName' => 'Max',
      'Species' => 'Dog',
      'Breed' => 'Labrador',
      'Color' => 'Light Golden',
      'LastSeenLocation' => '123 Park Lane',
      'LastSeenDate' => '2024-03-25',
      'PhotoURL' => 'Labrador.jpg',
      'ReportStatus' => "Pending"
    ],
    [
      'OwnerID' => 2,
      'PetName' => 'Whiskers',
      'Species' => 'Cat',
      'Breed' => 'Siamese',
      'Color' => 'Grey',
      'LastSeenLocation' => '456 Maple Street',
      'LastSeenDate' => '2024-03-27',
      'PhotoURL' => 'Siamese.jpg',
      'ReportStatus' => "Pending"
    ],
    [
      'OwnerID' => 3,
      'PetName' => 'Buddy',
      'Species' => 'Dog',
      'Breed' => 'Golden Retriever',
      'Color' => 'Golden',
      'LastSeenLocation' => '789 Oak Avenue',
      'LastSeenDate' => '2024-03-29',
      'PhotoURL' => 'Golden Retriever.jpg',
      'ReportStatus' => "Accepted"
    ],
    [
      'OwnerID' => 4,
      'PetName' => 'Mittens',
      'Species' => 'Cat',
      'Breed' => 'Persian',
      'Color' => 'Grey / White',
      'LastSeenLocation' => '101 Pine Road',
      'LastSeenDate' => '2024-04-01',
      'PhotoURL' => 'Persian.jpg',
      'ReportStatus' => "Accepted"
    ],
    [
      'OwnerID' => 5,
      'PetName' => 'Shadow',
      'Species' => 'Dog',
      'Breed' => 'German Shepherd',
      'Color' => 'Black',
      'LastSeenLocation' => '202 Willow Lane',
      'LastSeenDate' => '2024-04-02',
      'PhotoURL' => NULL,
      'ReportStatus' => "Pending"
    ],
  ];


  // Preparing the query for Lost Report table
  $query = 'INSERT INTO LostReport(OwnerID, PetName, Species, Breed, Color, LastSeenLocation, LastSeenDate, PhotoURL, ReportStatus) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

  // Preparing the statement with the query;
  $stmt = $conn->prepare($query);

  // For each record in the array, passing the corresponding data using for each loop
  foreach ($lostReport as $each) {

    // Handling potential empty values for PhotoURL
    $photoURL = $each['PhotoURL'] ? $each['PhotoURL'] : NULL;

    // Note that 's' is used for strings, 'i' for integers, and 'd' for dates or doubles in bind_param
    $stmt->bind_param("issssssss", $each['OwnerID'], $each['PetName'], $each['Species'], $each['Breed'], $each['Color'], $each['LastSeenLocation'], $each['LastSeenDate'], $each['PhotoURL'], $each['ReportStatus']);

    // After each successful binding of each record, executing the statement
    $result = $stmt->execute();
    if (!$result) {
      echo "Error inserting data for Pet Name: " .  $each['PetName'] . "<br>";
    }
  }
  echo "LostReport Table Populated Successfully!";
  $stmt->close();
}

//! ------------------------------

function createFinderTable($conn)
{
  $query = "CREATE TABLE IF NOT EXISTS Finder (
    FinderId INT NOT NULL AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    ContactNumber VARCHAR(15) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    PRIMARY KEY (FinderId),
    UNIQUE(ContactNumber),
    UNIQUE(Email),
    UNIQUE(Name, ContactNumber, Email)
)";

  // Executing the query 
  $result = $conn->query($query);

  // Based on the success result of the query echoing appropriate message
  if ($result) {
    echo "Finder table created or already exists!" . "<br>";
    insertRecordInFinders($conn);
  } else {
    echo "There was a problem creating the Finder table!" . $conn->error;
  }
}

function insertRecordInFinders($conn)
{
  // The Array that contains all the Finder information
  $finders = [
    [
      'Name' => 'John Doe',
      'ContactNumber' => '123-456-7890',
      'Email' => 'johnJ@example.com'
    ],
    [
      'Name' => 'Jane Smith',
      'ContactNumber' => '098-123-4532',
      'Email' => 'janeS@example.net'
    ],
    [
      'Name' => 'Bob Brown',
      'ContactNumber' => '453-123-5467',
      'Email' => 'bobB@example.org'
    ]
  ];

  // Preparing the query
  $query = 'INSERT INTO Finder(Name,ContactNumber,Email) VALUES(?,?,?)';

  // Preparing the statement with the query;
  $stmt = $conn->prepare($query);

  // For each record in the array passing the corresponding name contactnumber and the email using for each loop
  foreach ($finders as $each) {
    $stmt->bind_param("sss", $each['Name'], $each['ContactNumber'], $each['Email']);

    // After each successful binding of each record, executing the statement
    $result = $stmt->execute();
    if (!$result) {
      echo "Error inserting data for Name: " .  $each['Name'] . "<br>";
    }
  }
  echo "Finder Table Populated Succesfully!";
  $stmt->close();
}

//! ----------------

// This function creates the FinderReports table
function createFinderReportsTable($conn)
{
  // Query to create the finder report table 
  $query = "CREATE TABLE IF NOT EXISTS FinderReports (
    ReportID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    FinderID INT NOT NULL,
    FoundLocation VARCHAR(255) NOT NULL,
    FoundDate DATE NOT NULL,
    PhotoURL VARCHAR(255),
    Species VARCHAR(50) NOT NULL,
    Breed VARCHAR(50) NOT NULL,
    Color VARCHAR(50) NOT NULL,
    ReportStatus VARCHAR(20),
    ReportDate DATE DEFAULT (CURDATE()),

    FOREIGN KEY (FinderID) REFERENCES Finder(FinderID),
    UNIQUE(FinderID, FoundDate, Species, Breed, Color),
    INDEX(FoundDate),
    INDEX(Species),
    INDEX(Breed),
    INDEX(Color),
    INDEX(ReportStatus)
)";


  // Executing the query 
  $result = $conn->query($query);

  // Based on the success result of the query echoing appropriate message
  if ($result) {
    echo "FinderReports table created or already exists!" . "<br>";
    // Invoking the insertRecordInFinderReports() function to insert the values
    insertRecordInFinderReports($conn);
  } else {
    echo "There was a problem creating the FinderReports table!" . $conn->error;
  }
}


// Function to insert records into the FinderReports table
function insertRecordInFinderReports($conn)
{
  // The Array that contains all finder report information
  $finderReports = [
    [
      'FinderID' => 1,
      'FoundLocation' => '333 Apple la',
      'FoundDate' => '2024-04-09',
      'PhotoURL' => 'Macaw.jpg',
      'Species' => 'Bird',
      'Breed' => 'Macaw',
      'Color' => 'Blue',
      'ReportStatus' => 'Pending'
    ],
    [
      'FinderID' => 1,
      'FoundLocation' => 'Central Park',
      'FoundDate' => '2024-04-01',
      'PhotoURL' => 'DomesticShorthair.jpg',
      'Species' => 'Cat',
      'Breed' => 'Domestic Short hair',
      'Color' => 'Brown',
      'ReportStatus' => 'Pending'
    ],
    [
      'FinderID' => 2,
      'FoundLocation' => '123 Wild Lane',
      'FoundDate' => '2024-05-02',
      'PhotoURL' => 'Ragdoll.jpg',
      'Species' => 'Cat',
      'Breed' => 'Ragdoll',
      'Color' => 'White',
      'ReportStatus' => 'Accepted'
    ],
    [
      'FinderID' => 3,
      'FoundLocation' => '321 Lava Lane',
      'FoundDate' => '2024-04-03',
      'PhotoURL' => 'Beagle.jpg',
      'Species' => 'Dog',
      'Breed' => 'Beagle',
      'Color' => 'Red',
      'ReportStatus' => 'Pending'
    ]
  ];

  // Preparing the query for FinderReports table
  $query = 'INSERT INTO FinderReports(FinderID, FoundLocation, FoundDate, PhotoURL, Species, Breed, Color, ReportStatus) VALUES(?, ?, ?, ?, ?, ?, ?, ?)';

  // Preparing the statement with the query
  $stmt = $conn->prepare($query);

  // For each record in the array, passing the corresponding data using a foreach loop
  foreach ($finderReports as $each) {
    // Binding parameters for the INSERT statement
    $stmt->bind_param("isssssss", $each['FinderID'], $each['FoundLocation'], $each['FoundDate'], $each['PhotoURL'], $each['Species'], $each['Breed'], $each['Color'], $each['ReportStatus']);

    // Executing the statement
    $result = $stmt->execute();
    if (!$result) {
      echo "Error inserting data for Finder Id: " . $each['FinderID'] . "<br>";
    }
  }
  echo "FinderReports Table Populated Successfully!";
  $stmt->close();
}

//! ----------------

function createUsersTable($conn)
{
  // query to create the users table
  $query = "CREATE TABLE IF NOT EXISTS Users (
      UserName VARCHAR(100) NOT NULL,
      Password VARCHAR(255) NOT NULL,
      Email VARCHAR(100) NOT NULL,
      IsAdmin TINYINT(1),
      PRIMARY KEY (UserName),
      UNIQUE(Email),
      UNIQUE(UserName, Email)
    )";

  // Executing and saving the result true or false
  $result = $conn->query($query);

  // Based on the result echoing appropriate message
  if ($result) {
    echo "Users table created or already exsists!" . "<br>";
    // Invoking the insertRecordInOwners() to insert the records
    insertRecordInUsers($conn);
  } else {
    echo "There was a problem creating the Users table!";
  }
}

function insertRecordInUsers($conn)
{
  // The Array that contains all the Owners information
  $users = [
    [
      'UserName' => 'Alex123',
      'Password' => 'Alex123',
      'Email' => 'alexj@example.com',
      'IsAdmin' => 0
    ],
    [
      'UserName' => 'Obaedur123',
      'Password' => 'Obaedur123',
      'Email' => 'brendal@example.net',
      'IsAdmin' => 1
    ],
    [
      'UserName' => 'John123',
      'Password' => 'John123',
      'Email' => 'johnJ@example.com',
      'IsAdmin' => 0
    ]
  ];
  // Preparing the query
  $query = 'INSERT INTO Users(UserName,Password,Email,IsAdmin) VALUES(?,?,?,?)';

  // Preparing the statement with the query;
  $stmt = $conn->prepare($query);



  // For each record in the array passing the corresponding name contactnumber and the email using for each loop
  foreach ($users as $each) {
    $hashedPassword = password_hash($each['Password'], PASSWORD_DEFAULT);
    $stmt->bind_param("sssi", $each['UserName'], $hashedPassword, $each['Email'], $each['IsAdmin']);

    // After each successful binding of each record, executing the statement
    $result = $stmt->execute();
    if (!$result) {
      echo "Error inserting data for Name: " .  $each['UserName'] . "<br>" . $stmt->error;
    }
  }
  echo "Users Table Populated Succesfully!";
  $stmt->close();
}

//! ----------------

function createUsersReportsLinkTable($conn)
{
  // query to create the UsersReportsLink table
  $query = "CREATE TABLE IF NOT EXISTS UsersReportsLink (
    LinkID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserName VARCHAR(100) NOT NULL,
    LostReportID INT,
    FinderReportID INT,
    FOREIGN KEY (LostReportID) REFERENCES LostReport(ReportID),
    FOREIGN KEY (FinderReportID) REFERENCES FinderReports(ReportID),
    UNIQUE(LostReportID),
    UNIQUE(FinderReportID)
);";

  // Executing and saving the result true or false
  $result = $conn->query($query);

  // Based on the result echoing appropriate message
  if ($result) {
    echo "UsersReportsLink table created or already exsists!" . "<br>";
    // Invoking the insertRecordInUsersReportsLink() to insert the records
    insertRecordInUsersReportsLink($conn);
  } else {
    echo "There was a problem creating the UsersReportsLink table! " . $conn->error;
  }
}

function insertRecordInUsersReportsLink($conn)
{
  // The Array that contains all the usersReportLink information
  $usersReportLink = [
    [
      'UserName' => 'Alex123',
      'LostReportID' => 1,
      'FinderReportID' => NULL,
    ],
    [
      'UserName' => 'Alex123',
      'LostReportID' => 2,
      'FinderReportID' => NULL,
    ],
    [
      'UserName' => 'John123',
      'LostReportID' => NULL,
      'FinderReportID' => 1,
    ],
    [
      'UserName' => 'John123',
      'LostReportID' => NULL,
      'FinderReportID' => 2,
    ]
  ];
  // Preparing the query
  $query = 'INSERT INTO UsersReportsLink(UserName,LostReportID,FinderReportID) VALUES(?,?,?)';

  // Preparing the statement with the query;
  $stmt = $conn->prepare($query);



  // For each record in the array passing the corresponding information in each loop
  foreach ($usersReportLink as $each) {
    $stmt->bind_param("sii", $each['UserName'], $each['LostReportID'], $each['FinderReportID']);

    // After each successful binding of each record, executing the statement
    $result = $stmt->execute();
    if (!$result) {
      echo "Error inserting data for Name: " .  $each['UserName'] . "<br>" . $stmt->error;
    }
  }
  echo "UsersReportLink Table Populated Succesfully!";
  $stmt->close();
}


//! ------------------------------

// Calling the functions to create the table and insert data
createOwnersTable($conn);
createLostReportTable($conn);
createFinderTable($conn);
createFinderReportsTable($conn);
createUsersTable($conn);
createUsersReportsLinkTable($conn);
