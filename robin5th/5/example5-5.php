<?php
$a1 = "WILLIAM";
$a2 = "henry";
$a3 = "gatES";

echo $a1 . " " . $a2 . " " . $a3 . "<br>";
fix_names();
echo $a1 . " " . $a2 . " " . $a3;

// Since we are using the global keyword we are able to use the global scoped variables inside a function
function fix_names()
{
  global $a1;
  $a1 = ucfirst(strtolower($a1));
  global $a2;
  $a2 = ucfirst(strtolower($a2));
  global $a3;
  $a3 = ucfirst(strtolower($a3));
}
