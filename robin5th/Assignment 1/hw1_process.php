<!-- 
Name: Obaedur Rahman

Certification: I Certify that this submission is my own original work
 -->
<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Process</title>
</head>

<body>
 <h3 style="border: 1px solid; margin-bottom: 20px">BCS350 Assignment 1 -- Obaedur Rahman</h3>

 <?php

 //initializing variables with null values
 $plan = null;
 $minutes = null;
 // Creating a validPlans array with valid plans
 $validPlans = array("A", "B", "C");

 // Cheking if i enter the input name and the keys exist
 if (isset($_POST["plan"]) && isset($_POST["minute"])) {

  // Displaying the input values entered by the user
  echo "You entered plan " . strtoupper($_POST["plan"]) . ", " . $_POST["minute"] . " minutes<br><br>";

  // Validating the plan inputed by the user, created a seperate function which checks the input
  if (isValidPlan(strtoupper($_POST["plan"]))) {
   $plan = strtoupper($_POST["plan"]);
  } else {
   // Displaying the error message for invalid plan
   echo "Wrong plan entered, Please return to the previous page and enter a valid plan<br>";
   return;
  }

  // Validating the minutes entered by the user
  if (is_numeric($_POST["minute"]) && $_POST["minute"] >= 0) {
   $minutes = $_POST["minute"];
  } else {
   // Displaying the error message for invalid number
   echo "The number of minutes must be a numaric value and non-negetive. please return to the previous page and enter a valid value";
   return;
  }

  // if both the variables are not null, i call the getCost() function with valid arguments
  if ($minutes != null && $plan != null) {

   // Calling the getCost to print the cost of the plans and saving cost of plan A and plan B in a array
   $totalCostsOfPlans = getCost($minutes);

   // Based on the plan i am printing the current plan the user is on and corresponding charge of the plan
   if ($plan == "A") {
    echo "You have plan A. Your monthly charge is $" . $totalCostsOfPlans["A"];
    echo "<br><br>";
    calculateBestPlan($plan, $totalCostsOfPlans["A"], $totalCostsOfPlans);
   } else if ($plan == "B") {
    echo "You have plan B. Your monthly charge is $" . $totalCostsOfPlans["B"];
    echo "<br><br>";
    calculateBestPlan($plan, $totalCostsOfPlans["B"], $totalCostsOfPlans);
   } else {
    echo "You have plan C. Your monthly charge is $69.99";
    echo "<br><br>";
    calculateBestPlan($plan, 69.99, $totalCostsOfPlans);
   }
  }
 } else {
  echo "Check the name attribute in your input elemnt";
 }


 // The getCost function, prints the assocciated cost for each plan, then bundles and returns the costs as a key value pair array
 function getCost($minutes)
 {
  $planACost = (29.99 + calculateAdditionalCost("450", $minutes, 0.25));
  $planBCost = (49.99 + calculateAdditionalCost("900", $minutes, 0.15));
  printf("Plan A cost: $%.2f", $planACost);
  echo "<br>";
  printf("Plan B cost: $%.2f", $planBCost);
  echo "<br>";
  echo "Plan C cost: $69.99";
  echo "<br><br>";
  return array("A" => $planACost, "B" => $planBCost);
 }


 // This function calculates the additional cost for any extra minutes the user spent
 function calculateAdditionalCost($availableMinutes, $usedMinute, $additionalUsageCost)
 {
  $extraMinutes = $availableMinutes - $usedMinute;
  if ($extraMinutes < 0) {
   return abs($extraMinutes) * $additionalUsageCost;
  } else {
   return 0;
  }
 }


 // This function checks if the plan is valid or not
 function isValidPlan($plan)
 {
  // defining the $validPlans array as global
  global $validPlans;

  // in_array checks if user's input is in the $validPlans array, returns true it the value exists, and false otherwise
  if (in_array($plan, $validPlans)) {
   return true;
  } else {
   return false;
  }
 }

 // This function takes chosen user's plan, cost of the chosen plan, and arrays of the costs of plans, 
 // Uses logic to derive the best plan possible, and how much the user could've saved
 function calculateBestPlan($plan, $costOfChosenePlan, $totalCostsOfPlans)
 {
  if ($plan == "A") {
   if ($costOfChosenePlan > 69.99) {
    printf("By switching to plan B, you would have saved $%.2f", ($costOfChosenePlan - $totalCostsOfPlans["B"]));
    echo "<br>";
    printf("By switching to plan C, you would have saved $%.2f", ($costOfChosenePlan - 69.99));
   } else if ($costOfChosenePlan > 49.99) {
    printf("By switching to plan B, you would have saved $%.2f", ($costOfChosenePlan - $totalCostsOfPlans["B"]));
   } else {
    echo "<br>";
    echo "You have chosen the best plan";
   }
  } else if ($plan == "B") {
   if ($costOfChosenePlan > 69.99) {
    printf("By switching to plan C, you would have saved $%.2f", ($costOfChosenePlan - 69.99));
   } else if ($totalCostsOfPlans["A"] < 49.99) {
    printf("By switching to plan A, you would have saved $%.2f", (49.99 - $totalCostsOfPlans["A"]));
   } else {
    echo "You have chosen the best plan";
   }
  } else {
   if ($totalCostsOfPlans["A"] < 69.99 && $totalCostsOfPlans["B"] < 69.99) {
    printf("By switching to plan A, you would have saved $%.2f", (69.99 - $totalCostsOfPlans["A"]));
    echo "<br>";
    printf("By switching to plan B, you would have saved $%.2f", (69.99 - $totalCostsOfPlans["B"]));
   } else if ($totalCostsOfPlans["B"] < 69.99) {
    printf("By switching to plan B, you would have saved $%.2f", (69.99 - $totalCostsOfPlans["B"]));
   } else {
    echo "<br>";
    echo "You have chosen the best plan";
   }
  }
 }
 ?>

</body>

</html>