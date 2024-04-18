<?php
// This module is an example of shallow copy, first creating an object of user class called object1 and then changing its name.
$object1 = new User();
$object1->name = "Alice";

// Here saving the reference of object1 in object2, now both the objects are pointing to same location in the memory
$object2 = $object1;

// Therefore changing the properties of one object wiuld effect in both
$object2->name = "Amy";

echo "object1 name = " . $object1->name . "<br>";
echo "object2 name = " . $object2->name;

class User
{
  public $name;
}
