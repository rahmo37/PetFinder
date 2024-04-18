<!-- I certify that this submission is my own original work -->
<?php
// Importing the login file
require_once 'hw4_login.php';

// Creating a new connection with appropriate login info
$connection = new mysqli($host, $user, $pass, $data);

// If there was any error we end the program
if ($connection->connect_error) {
  die("Connection failed: " . $connection->connect_error);
}

// An heredoc string to display my name and the assignment
$fieldset = <<<END
<fieldset>
<legend>Assignment 4</legend>
<label>Obaedur Rahman</label>
</fieldset>
END;
echo $fieldset;

// Gathering all the vaid fields in an array
$validFields = ['sid', 'name', 'email', 'start_year', 'gpa', 'phone'];

// If the the $_POST['field] is set, and the $_POST['field'] is in the list of $validFields then the $sortBy variable is set to $_POST['field']. Otherwise $sortBy is set sid
$sortBy = isset($_POST['field']) && in_array($_POST['field'], $validFields) ? $_POST['field'] : 'sid';

// The $option variable is going to be used to build the dropdown options
$options = '';

// Here i am going run the foreach function to select each field and compare it with the previously selected sortBy variable to check which field is currently selected, upon evaluation i am going add the 'selected' attributes to that <Option> element so that field remains selected
foreach ($validFields as $field) {
  $selected = $sortBy === $field ? 'selected' : ''; // If matches selected attribute will be added to the option element
  $options .= "<option value=\"$field\" $selected>" . ucfirst($field) . "</option>"; // Constructing the option element with the selected value (either 'selected' or "") if applicable, then each <option> element is combined inside the $optionts variable
}

// Here i am builing the form. Using post method and concatinating the options
echo "<h3>List Records in the Student Table</h3>";
echo <<<FORM
<div class="form-container">
<form action="hw4_listRecord.php" method="post"> 
    <label for="field">Sort By</label>
    <select name="field" id="field">
        $options
    </select>
    <input class="button" type="submit" value="Submit">
</form>
</div>
FORM;

// Building the query to retrieve the data from the student table with correct order, selected by the user.
$query = "SELECT * FROM student ORDER BY $sortBy";
$result = $connection->query($query); // Executing the query, the result will be saved in the $result variable

// Fetching each record from the result and saving each returned row in the $row variable, finally fetching the columns from the row and setting them in the table. Using the htmlspecialchars to sanitize the data
if ($result) {
  echo "<table>";
  echo "<tr><th>SID</th><th>Name</th><th>Email</th><th>Phone</th><th>Start Year</th><th>GPA</th></tr>"; // Head column
  while ($row = $result->fetch_assoc()) {
    echo "<tr><td>" . htmlspecialchars($row['sid']) . "</td>";
    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
    echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
    echo "<td>" . htmlspecialchars($row['start_year']) . "</td>";
    echo "<td>" . htmlspecialchars($row['gpa']) . "</td></tr>";
  }
  echo "</table>";
} else {
  echo "Error fetching records: " . $connection->error;
}

// Closing the connection
$connection->close();


// Finally Adding a bit of CSS to style and align the table also stying the dropdown menu
echo "<style>
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #fdfd96;
  }

  tr:nth-child(odd) {
    background-color: #ffffe0;
  }
  .form-container {
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
    max-width: 300px;
    margin: 20px auto;
    padding: 20px;
    background: #f5f5dc;
    border-radius: 8px;
    box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.1);
  }
  
  .form-container form {
    width: 100%;
  }
  
  label {
    display: block;
    margin-bottom: 15px;
    text-align: center;
    color: #333333;
    font-size: 16px;
    font-weight: bold;
  }
  
  select, .button {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccd0d5;
    border-radius: 4px;
    box-sizing: border-box;
    text-align: center;
  }
  
  .button {
    background-color: #ff7f50;
    border: none;
    color: white;
    padding: 10px 20px;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    outline: none;
    transition: all 0.2s, box-shadow 0.2s;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  }

  .button:hover {
    background-color: #ff4500;
  }

  .button:active {
    background-color: #ffb300;
    transform: translateY(4px);
    box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.2);
  }
</style>";
