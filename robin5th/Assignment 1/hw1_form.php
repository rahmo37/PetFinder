<!-- 
Name: Obaedur Rahman

Certification: I Certify that this submission is my own original work
 -->


<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <style>
  p,
  h3 {
   margin-top: 0px;
   margin-bottom: 10px;
  }

  form {
   display: grid;
   width: fit-content;
   padding: 20px;
   align-items: left;
   justify-content: left;
   background-color: #ffa07a;
  }

  form input {
   margin-bottom: 15px;
   border: none;
   border-radius: 5px;
   padding: 2%;
  }

  #submit {
   margin-top: 20px;
   transition: 0.08s all ease-in;
  }

  #submit:active {
   transform: scale(0.95);
  }

  #submit:hover {
   background-color: #ffcc99;
  }
 </style>
 <title>Form</title>
</head>

<body>
 <h3 style="border: 1px solid; margin-bottom: 20px">BCS350 Assignment 1 -- Obaedur Rahman</h3>
 <h3>Mobile Phone Service Plan</h3>
 <p>Plan A: $29.99 per month. 450 free minutes. Additional usage costs $0.25 per minute</p>
 <p>Plan B: $49.99 per month. 900 free minutes. Additional usage costs $0.15 per minute</p>
 <p>Plan C: $69.99 per month. Unlimited minutes</p>
 <br>

 <form method="post" action="hw1_process.php">
  <h3>Enter your plan and minutes used:</h3>
  Plan: <input type="text" id="plan" name="plan">
  Minutes: <input type="text" id="minute" value="0" name="minute">
  <input type="submit" value="Calculate Monthly Charge" id="submit">
 </form>
</body>

</html>