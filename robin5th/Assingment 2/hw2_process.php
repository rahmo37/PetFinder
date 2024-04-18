<!-- 
Name: Obaedur Rahman

Certification: I Certify that this submission is my own original work
 -->

<style>
  @import url("https://fonts.googleapis.com/css2?family=Antic&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");

  /* Font used by the whole page */
  body {
    font-family: "Roboto", sans-serif;
  }

  /* Helps me format the web page */
  .patient-info label {
    display: inline-block;
    width: 160px;
    text-align: left;
    margin-right: 10px;
  }

  /* Format the image */
  img {
    width: 350px;
    height: 400px;
    object-fit: fit;
    margin: 20px;
  }

  p {
    margin-bottom: 15px;
    margin-top: 15px;
  }
</style>

<!-- Using filedset to set the header -->
<fieldset>
  <legend>
    Assignment Information
  </legend>
  <label>BCS360 Assignment 2 -- Obaedur Rahman</label>
</fieldset>
<?php

// invoking the displayPatientProfile();
displayPatientProfile();


// This function displayes the patients profile
function displayPatientProfile()
{
  // sets the patients profile picture
  setProfilePicture();

  // creating new patient instance
  $newPatient = createPatient();

  // Sending arguments to the printInformation function which takes the label and value
  printInformation("First Name:", $newPatient->getFirstName());
  printInformation("Last Name:", $newPatient->getLastName());
  printInformation("Date of Birth:", $newPatient->getDob());
  printInformation("Email:", $newPatient->getEmail());
  printInformation("Gender:", ucfirst($newPatient->getGender()));
  printInformation("Marital Satus:", $newPatient->getMStatus());

  // If the size of the disease array is greater than 0, we print each diease in the window
  if (sizeof($newPatient->getDisease()) > 0) {
    for ($i = 0; $i < sizeof($newPatient->getDisease()); $i++) {
      if ($i == 0) {
        // First disease in printed with the label
        printInformation("Family Health History:", $newPatient->getDisease()[$i]);
      } else {
        printInformation("", $newPatient->getDisease()[$i]);
      }
    }
  } else {
    // Else if the disease array is empty we print appropriate message
    printInformation("Family Health History:", "No listed family health history reported");
  }

  // Extra information if there are any
  printInformation("Extra Information:", $newPatient->getExtraInfo());
  echo '<br>';

  // Timestamp
  // Setting the default zone to New York
  date_default_timezone_set('America/New_York');

  // Appropriate format of the date and time as specified in the assignment
  echo "Patient registered on " . date("l, F d, Y \a\\t g:i a.");
}

// Print information as HTML label, it allowed me to format the strings more precisely by applying some CSS at the top
// Each information is sent to this method thus printing the passed in label and its corresponding value
function printInformation($lable, $value)
{
  echo "<div class=" . "patient-info" . ">";
  echo "<label>$lable</label>" . $value . "<br>";
  echo "</div>";
}

// This method makes necessary validation to set the profile picture
function setProfilePicture()
{
  // Cheking there are any errors, error value is greater than 0 if no image was uploaded. Essentially checking if there were any image uploaded or not
  if ($_FILES['profilePicture']['error'] == 0) {

    // Saving required values in their corresponding variables
    $name = $_FILES['profilePicture']['name'];
    $type = $_FILES['profilePicture']['type'];
    $tempName = $_FILES['profilePicture']['tmp_name'];

    $ext = '';

    // if the extension of the images matches one of these predefined extension then we set appropriate value for that extension and save it in the $ext variable
    switch ($type) {
      case 'image/jpeg':
        $ext = '.jpg';
        break;
      case 'image/gif':
        $ext = '.gif';
        break;
      case 'image/png':
        $ext = '.png';
        break;
      case 'image/tif':
        $ext = '.tif';
        break;
      default:
        $ext = '';
        break;
    }
    // if the $ext is not empty we set, meaing the image format is acceptable, then we save it in the server with new image name
    if (!empty($ext)) {
      $imageName = "profilePicture" . $ext;
      move_uploaded_file($tempName, $imageName);
      echo "<img src='$imageName'>";
    } else {
      // Else we display error message
      echo "<p>Warning: $name is not an accepted file format.</p>";
    }
  } else {
    // if the error's value was other than 0, the file was not uploade
    echo "<p>Patient photo was not uploaded.</p>";
  }
}


// Creates a new patient
function createPatient()
{
  // Temporary local variables where all the values are first checked if exists then set
  $fn = checkKey('firstName');
  $ln = checkKey('lastName');
  $db = checkKey('dateOfBirth');
  $em = checkKey('email');
  $gn = checkKey('gender');
  $ms = checkKey('maritalStatus');
  $ei = checkKey('extraInfo');

  // Here we are directly seeing if the disease array is set and if the value is actually an array, if it is then we set it to the temporary variable $ds
  if (isset($_POST['disease']) && is_array($_POST['disease'])) {
    $ds = $_POST['disease'];
  } else {
    // if no key is set for disease or the value passed is not an array we simply add an empty array
    $ds = [];
  }

  // creates a new patient
  return new patient($fn, $ln, $db, $em, $gn, $ms, $ei, $ds);
}



// The form.php already retuns all the key by design, but just an extra layer of protection
function checkKey($key)
{
  if (isset($_POST[$key])) {
    return $_POST[$key];
  } else {
    return "no value set";
  }
}

// In chapter 7 we learned about class and its attributes, so i thought it i would be great opportunity to implement them in this assignment. Thats why i desined the structure in an object oriented way

// This is the patient class sanitizes all the value by calling the set method in the constructor to set their value, each value has their corresponding setter and getter. the setter methods sanitizes the value

// SanitizeInput and sanitizeArray are static method with in the class
class patient
{
  private $firstName;
  private $lastName;
  private $dob;
  private $email;
  private $gender;
  private $mStatus;
  private $disease;
  private $extraInfo;

  public function __construct($firstName, $lastName, $dob, $email, $gender, $mStatus, $extraInfo, $disease)
  {
    // when values are passed each value is checked with its corresponding setMethods to add security
    $this->setFirstName($firstName);
    $this->setLastName($lastName);
    $this->setDob($dob);
    $this->setEmail($email);
    $this->setGender($gender);
    $this->setMStatus($mStatus);
    $this->setExtraInfo($extraInfo);
    $this->setDisease($disease);
  }

  // Setter for firstName
  public function setFirstName($firstName)
  {
    $this->firstName = self::sanitizeInput($firstName);
  }

  // Setter for lastName
  public function setLastName($lastName)
  {
    $this->lastName = self::sanitizeInput($lastName);
  }

  // Setter for dob (Date of Birth)
  public function setDob($dob)
  {
    $this->dob = self::sanitizeInput($dob);
  }

  // Setter for email
  public function setEmail($email)
  {
    $this->email = self::sanitizeInput($email);
  }

  // Setter for gender
  public function setGender($gender)
  {
    $this->gender = self::sanitizeInput($gender);
  }

  // Setter for mStatus (Marital Status)
  public function setMStatus($mStatus)
  {
    $this->mStatus = self::sanitizeInput($mStatus);
  }


  // Setter for extraInfo
  public function setExtraInfo($extraInfo)
  {
    if (!empty($extraInfo)) {
      $this->extraInfo = self::sanitizeInput($extraInfo);
    } else {
      $this->extraInfo = "No extra information entered";
    }
  }


  public function setDisease($disease)
  {
    if (sizeof($disease) > 0) {
      $this->disease = self::sanitizeArray($disease);
    } else {
      $this->disease = $disease;
    }
  }

  // Getter for firstName
  public function getFirstName()
  {
    return $this->firstName;
  }

  // Getter for lastName
  public function getLastName()
  {
    return $this->lastName;
  }

  // Getter for dob (Date of Birth)
  public function getDob()
  {
    return $this->dob;
  }

  // Getter for email
  public function getEmail()
  {
    return $this->email;
  }

  // Getter for gender
  public function getGender()
  {
    return $this->gender;
  }

  // Getter for mStatus (Marital Status)
  public function getMStatus()
  {
    return $this->mStatus;
  }

  // Getter for disease
  public function getDisease()
  {
    return $this->disease;
  }

  // Getter for extraInfo
  public function getExtraInfo()
  {
    return $this->extraInfo;
  }

  // This static method sanitize the value passed
  static function sanitizeInput($value)
  {
    return htmlentities($value);
  }

  // The static method specifically sanitizes an array, each element is sanitized, then recompiled and returned
  static function sanitizeArray($arr)
  {
    $sanitizedArr = [];
    foreach ($arr as $item) {
      $sanitizedArr[] = htmlentities($item);
    }
    return $sanitizedArr;
  }
}
