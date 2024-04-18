<?php
// In this module we are doing the deep copy.each object is now seperate. changing a porperty of one object would not affect the other object
$object1 = new User();
$object1->name = "Alice";
$object2 = clone $object1;
$object2->name = "Amy";


echo "object1 name = " . $object1->name . "<br>";
echo "object2 name = " . $object2->name;

class User
{
  public $name;
}
