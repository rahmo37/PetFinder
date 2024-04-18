<?php
$object = new User;
// The print_r() function in PHP is used to print human-readable information about a variable. It can display the value of a variable, including arrays and objects, in a format that's easier to understand compared to other output functions like echo. For arrays, print_r() shows the keys and values, and for objects, it displays properties and their values. This function is particularly useful for debugging purposes, allowing developers to inspect the contents of variables.
print_r($object);
class User
{
  public $name, $password;

  function save_user()
  {
    echo "Save User code goes here";
  }
}
