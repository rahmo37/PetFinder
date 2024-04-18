<!-- 
Name: Obaedur Rahman

Certification: I Certify that this submission is my own original work
 -->

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Create Patient Profile</title>

 <link rel="stylesheet" href="style.css">
</head>

<body>
 <fieldset>
  <legend>
   Assignment Information
  </legend>
  <label>BCS360 Assignment 2 -- Obaedur Rahman</label>
 </fieldset>
 <h1>Create Patient Profile</h1>


 <form action="./hw2_process.php" method="post" enctype='multipart/form-data'>
  <div id="container">

   <div>
    <label for="firstName">First Name:</label>
    <input type="text" id="firstName" name="firstName" required>
    <img id="clipboard" src="./clipBoardDONOTDELETE.gif" alt="">
   </div>

   <div>
    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName" required>
   </div>

   <div>
    <label for="dateOfBirth">Date of Birth:</label>
    <input type="date" id="dateOfBirth" name="dateOfBirth" required>
   </div>

   <div>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
   </div>

   <div>
    <label>Gender:</label>
    <input type="radio" name="gender" value="male" style="margin-right: -13px;" required>Male
    <input type="radio" name="gender" value="female" style="margin-right: -13px;" required>Female
   </div>


   <div>
    <label>Marital Status</label>
    <select name="maritalStatus">
     <option value="Single">Single</option>
     <option value="Married">Married</option>
     <option value="Widowed">Widowed</option>
    </select>
   </div>


   <div id="disease_container">
    <label>Family Health History</label>
    <div id="disease_box">
     <label><input type="checkbox" name="disease[]" value="Asthma"> Asthma</label>
     <label><input type="checkbox" name="disease[]" value="Cancer"> Cancer</label>
     <label><input type="checkbox" name="disease[]" value="Depression"> Depression</label>
     <label><input type="checkbox" name="disease[]" value="Diabetes"> Diabetes</label>
     <label><input type="checkbox" name="disease[]" value="Heart Disease"> Heart Disease</label>
     <label><input type="checkbox" name="disease[]" value="High Blood Pressure"> High Blood Pressure</label>
     <label><input type="checkbox" name="disease[]" value="High Cholesterol"> High Cholesterol</label>
     <label><input type="checkbox" name="disease[]" value="Stroke"> Stroke</label>
    </div>
   </div>


   <div>
    <label>Extra Information</label>
    <textarea name="extraInfo" id="extraInfo" cols="30" rows="5"></textarea>
   </div>



   <div>
    <label>Upload Profile Picture (JPG, GIF, PNG or TIF)</label>
    <input type="file" id="profilePicture" name="profilePicture">
   </div>

   <div class="button-container"> <input type="submit" Value="Submit Form">
    <input type="reset" Value="Reset Form">
   </div>


  </div>
 </form>
</body>

</html>