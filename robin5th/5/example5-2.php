<?php
echo fix_names("WILLIAM", "henry", "gatES");

function fix_names($n1, $n2, $n3)
{
  // ucfirst upper cases the first character of the string and the strlower() function lower cases the whole stinrg, in the below code we are first lowercasing the entire string and then upper casing the first character
  $n1 = ucfirst(strtolower($n1));
  $n2 = ucfirst(strtolower($n2));
  $n3 = ucfirst(strtolower($n3));

  return $n1 . " " . $n2 . " " . $n3;
}
